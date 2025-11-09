<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ReceiptsController;
use App\Models\Person;
use App\Models\Delivery;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Procesa el checkout completo:
     *  - valida payload entrante (front)
     *  - busca o crea persona (consulta API externa si no existe)
     *  - delega creación de receipt a ReceiptsController (mapeando articulos)
     *  - crea registro de delivery
     *  - todo en transacción
     */
    public function store(Request $request)
    {
        // Validación básica de los campos principales (artículos validados más abajo)
        $baseRules = [
            'document_type' => 'required|in:DNI,RUC',
            'document_number' => 'required|string|max:20',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:30',

            'tipo_entrega' => 'required|in:delivery,tienda',
            'direccion' => 'nullable|string|max:255',
            'referencia' => 'nullable|string|max:255',
            'latitud' => 'nullable|numeric',
            'longitud' => 'nullable|numeric',

            'receipt_code' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Compra,Venta',

            'articulos' => 'required|array|min:1',
        ];

        $validator = Validator::make($request->all(), $baseRules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validación de campos inicial falló.',
                'errors' => $validator->errors()
            ], 422);
        }

        $payload = $request->all();

        // Validar artículos con estructura flexible:
        // El front puede enviar { id, price, quantity } o { item_id, price, quantity }.
        foreach ($payload['articulos'] as $idx => $art) {
            // Compruebo que tenga id o item_id
            $itemId = $art['item_id'] ?? $art['id'] ?? null;
            if (!$itemId) {
                return response()->json([
                    'status' => false,
                    'message' => "El artículo en la posición {$idx} no tiene 'id' ni 'item_id'."
                ], 422);
            }

            // Verifico existencia del item
            $item = Item::find($itemId);
            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => "El artículo con id {$itemId} (posición {$idx}) no existe."
                ], 422);
            }

            // Verifico quantity y price mínimos
            if (!isset($art['quantity']) || floatval($art['quantity']) <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => "La cantidad del artículo id {$itemId} es inválida."
                ], 422);
            }
            if (!isset($art['price']) || !is_numeric($art['price']) || floatval($art['price']) < 0) {
                return response()->json([
                    'status' => false,
                    'message' => "El precio del artículo id {$itemId} es inválido."
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            // -------------------------
            // 1) Buscar o crear PERSONA
            // -------------------------
            $documentType = $payload['document_type'];
            $documentNumber = $payload['document_number'];

            $person = Person::where('identification_number', $documentNumber)->first();

            if (!$person) {
                // Intentamos obtener datos desde API externa (apiperu.dev)
                $name = null;
                $last_name = null;
                $razon_social = null;

                $apiBase = "https://apiperu.dev/api";
                // Nota: el token está en tu código. Considera moverlo a .env
                $apiToken = env('APIPERU_TOKEN', '4476d4d57283b7297ee7193d2452a4ba5d6454a7d4d5f0ccd53d0711c551f22f');

                try {
                    if ($documentType === 'DNI') {
                        $resp = Http::withHeaders([
                            'Authorization' => "Bearer {$apiToken}",
                            'Accept' => 'application/json',
                        ])->post("{$apiBase}/dni", ['dni' => $documentNumber]);

                        if ($resp->ok()) {
                            $data = $resp->json();
                            if (isset($data['data'])) {
                                $name = $data['data']['nombres'] ?? null;
                                $last_name = trim(($data['data']['apellido_paterno'] ?? '') . ' ' . ($data['data']['apellido_materno'] ?? ''));
                            }
                        }
                    } elseif ($documentType === 'RUC') {
                        $resp = Http::withHeaders([
                            'Authorization' => "Bearer {$apiToken}",
                            'Accept' => 'application/json',
                        ])->post("{$apiBase}/ruc", ['ruc' => $documentNumber]);

                        if ($resp->ok()) {
                            $data = $resp->json();
                            if (isset($data['data'])) {
                                $razon_social = $data['data']['nombre_o_razon_social'] ?? null;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // No detener el flujo si la API externa falla
                    Log::warning("API externa DNI/RUC falló: " . $e->getMessage());
                }

                // Crear persona con los datos encontrados (o valores por defecto)
                $person = Person::create([
                    'name' => $name ?? $razon_social ?? 'Cliente',
                    'last_name' => $last_name ?? '',
                    'email' => $payload['email'],
                    'phone' => $payload['phone'] ?? null,
                    'type' => 'Cliente',
                    'identification_type' => $documentType,
                    'identification_number' => $documentNumber,
                ]);
            } else {
                // Si existe, actualizamos datos de contacto (pero no sobreescribimos nombre si está lleno)
                $person->update([
                    'email' => $payload['email'],
                    'phone' => $payload['phone'] ?? $person->phone,
                ]);
            }

            // -------------------------
            // 2) Preparar y crear RECEIPT (delegar a ReceiptsController)
            // -------------------------
            // Mapear los articulos al formato que ReceiptsController espera:
            // item_id, quantity, unit (si existe), price
            $mappedArticulos = [];
            foreach ($payload['articulos'] as $art) {
                $itemId = $art['item_id'] ?? $art['id'];
                // Si frontend no envía unidad, tomar item->unit_measurement o 'UND'
                $unit = $art['unit'] ?? null;
                if (!$unit) {
                    $itemModel = Item::find($itemId);
                    $unit = $itemModel ? ($itemModel->unit_measurement ?? 'UND') : 'UND';
                }

                $mappedArticulos[] = [
                    'item_id' => $itemId,
                    'quantity' => $art['quantity'],
                    'unit' => $unit,
                    'price' => $art['price'],
                ];
            }

            // Preparar Request para ReceiptsController
            $receiptPayload = [
                'receipt_code' => $payload['receipt_code'],
                'description' => $payload['description'],
                'user_id' => $payload['user_id'],
                'person_id' => $person->id,
                'type' => $payload['type'],
                'articulos' => $mappedArticulos,
            ];

            // Llamada interna a ReceiptsController::store
            $receiptController = new ReceiptsController();

            // Nota: usar Request para simular llamada HTTP
            $receiptRequest = new Request($receiptPayload);

            // Importante: si ReceiptsController lanza excepción (por stock insuficiente u otro),
            // caerá al catch general y hará rollback
            $receiptResponse = $receiptController->store($receiptRequest);

            // $receiptResponse es JsonResponse; obtener datos
            $receiptData = $receiptResponse->getData(true)['receipt'] ?? null;

            if (!$receiptData) {
                throw new \Exception('No se pudo crear el comprobante (respuesta vacía).');
            }

            // -------------------------
            // 3) Crear DELIVERY (si aplica)
            // -------------------------
            //(siempre registrar, sea tienda o delivery)
            $deliveryRecord = Delivery::create([
                'fk_persona' => $person->id,
                'fk_comprobante' => $receiptData['id'],
                'direccion' => $payload['tipo_entrega'] === 'delivery' ? ($payload['direccion'] ?? null) : 'Recojo en tienda principal',
                'referencia' => $payload['tipo_entrega'] === 'delivery' ? ($payload['referencia'] ?? null) : 'Av. Tu Tienda 123',
                'latitud' => $payload['tipo_entrega'] === 'delivery' ? ($payload['latitud'] ?? null) : null,
                'longitud' => $payload['tipo_entrega'] === 'delivery' ? ($payload['longitud'] ?? null) : null,
                'tipo_entrega' => $payload['tipo_entrega'],
                'estado' => 'E',
                'fecha_programada' => $payload['fecha_programada'] ?? null,
                'observaciones' => $payload['observaciones'] ?? 'Pedido generado vía checkout',
                'hashOrden' => Str::uuid(), // <-- aquí se genera el hash único
            ]);


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Compra registrada exitosamente.',
                'data' => [
                    'person' => $person,
                    'receipt' => $receiptData,
                    'delivery' => $deliveryRecord,
                ],
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            // Log completo para depuración
            Log::error('Error en checkout: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            $msg = $e->getMessage();

            // Si es excepción lanzada por ReceiptsController por stock insuficiente, devolver 400
            if (stripos($msg, 'No hay suficiente stock') !== false) {
                return response()->json([
                    'status' => false,
                    'message' => $msg
                ], 400);
            }

            return response()->json([
                'status' => false,
                'message' => 'Error al procesar la compra.',
                'error' => $msg
            ], 500);
        }
    }

    public function showByHash($hash)
{
    // Usar 'receipt_details' en lugar de 'articulos'
    $delivery = Delivery::with('person', 'receipt.receipt_details') // Cambiar 'articulos' por 'receipt_details'
        ->where('hashOrden', $hash)
        ->first();

    if (!$delivery) {
        return response()->json([
            'status' => false,
            'message' => 'Orden no encontrada'
        ], 404);
    }

    // Agregar más detalles a la respuesta
    $receiptDetails = $delivery->receipt->receipt_details->map(function($item) {
        return [
            'item_name' => $item->item->name,   // Nombre del artículo
            'quantity' => $item->quantity,      // Cantidad de artículos
            'price' => $item->price,            // Precio de cada artículo
            'subtotal' => $item->subtotal,      // Subtotal de ese artículo
            'unit' => $item->unit,              // Unidad del artículo
        ];
    });

    return response()->json([
        'status' => true,
        'delivery' => $delivery,
        'person' => $delivery->person,         // Información de la persona
        'receipt' => $delivery->receipt,       // Detalles del recibo
        'receipt_details' => $receiptDetails,  // Detalles completos de los artículos
        'direccion' => $delivery->direccion,  // Dirección de entrega
        'referencia' => $delivery->referencia,// Referencia de la entrega
        'estado' => $delivery->estado,        // Estado de la entrega
        'observaciones' => $delivery->observaciones, // Observaciones adicionales
    ]);
}



}
