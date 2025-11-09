<?php

namespace App\Http\Controllers\Api;

use App\Models\Item;
use App\Models\Receipt;
use App\Models\Movement;
use Illuminate\Http\Request;
use App\Models\ReceiptDetail;
use App\Models\UnitConversion;
use App\Http\Controllers\Controller;

class ReceiptsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receipts = Receipt::with('user.person', 'person')->get();
        return response()->json([
            'status' => true,
            'receipts' => $receipts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar datos básicos del comprobante
        $validated = $request->validate([
            'receipt_code' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'person_id' => 'required|exists:people,id',
            'type' => 'required|in:Compra,Venta',
            'articulos' => 'required|array|min:1',
            'articulos.*.item_id' => 'required|exists:items,id',
            'articulos.*.quantity' => 'required|min:1',
            'articulos.*.unit' => 'nullable|string|max:50',
            'articulos.*.price' => 'required|numeric|min:0',
        ]);

        // Crear cabecera del comprobante
        $receipt = Receipt::create([
            'receipt_code' => $validated['receipt_code'],
            'description' => $validated['description'],
            'user_id' => $validated['user_id'],
            'person_id' => $validated['person_id'],
            'type' => $validated['type'],
        ]);

        // Procesar los detalles del comprobante
        foreach ($validated['articulos'] as $articulo) {
            // Calcular subtotal del artículo
            $subtotal = $articulo['quantity'] * $articulo['price'];

            // Crear detalle del comprobante
            $detail = ReceiptDetail::create([
                'receipt_id' => $receipt->id,
                'item_id' => $articulo['item_id'],
                'quantity' => $articulo['quantity'],
                'unit' => $articulo['unit'],
                'price' => $articulo['price'],
                'subtotal' => $subtotal,
            ]);

            // Registrar movimiento de inventario
            Movement::create([
                'item_id' => $articulo['item_id'],
                'user_id' => $validated['user_id'],
                'receipt_id' => $receipt->id,
                'receipt_detail_id' => $detail->id,
                'quantity' => $articulo['quantity'],
                'type' => $validated['type'],
                'price' => $articulo['price'],
                'stock' => $this->calculateNewStock($articulo['item_id'], $validated['type'], $articulo['quantity'], $articulo['unit']),
            ]);
        }

        // Cargar relaciones para la respuesta
        $receipt->load(['user.person', 'person', 'receipt_details']);

        return response()->json([
            'status' => true,
            'message' => "Comprobante registrado exitosamente.",
            'receipt' => $receipt
        ], 201);
    }

    /**
     * Calcula el nuevo stock con validación de ventas sin inventario.
     */
    private function calculateNewStock($itemId, $type, $quantity, $convert_unit)
    {
        $item = Item::find($itemId);
        if (!$item) return 0;

        $item_unit = $item->unit_measurement;

        // Último movimiento registrado
        $lastMovement = Movement::where('item_id', $itemId)
            ->orderBy('id', 'desc')
            ->first();

        $lastStock = $lastMovement ? $lastMovement->stock : 0;

        // Factor de conversión
        $conversionFactor = 1;
        if ($convert_unit && $convert_unit !== $item_unit) {
            $conversion = UnitConversion::where('comercial_unit', $convert_unit)->first();
            if ($conversion) {
                $conversionFactor = $conversion->conversion_factor ?: 1;
            }
        }

        // Convertir cantidad a unidad base
        $cantidadConvertida = $quantity / $conversionFactor;

        // 🔹 Validar stock suficiente antes de vender
        if ($type === 'Venta' && $lastStock < $cantidadConvertida) {
            throw new \Exception("No hay suficiente stock para realizar la venta del artículo ID $itemId.");
        }

        // Calcular nuevo stock
        $newStock = $type === 'Compra'
            ? $lastStock + $cantidadConvertida
            : $lastStock - $cantidadConvertida;

        return round($newStock, 3);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        $receipt->update($request->all());
        $receipt->load(['user.person', 'person']);
        return response()->json([
            'status' => true,
            'message' => "Receipt updated successfully!",
            'receipt' => $receipt
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return response()->json([
            'status' => true,
            'message' => 'Receipt deleted successfully!'
        ], 200);
    }
}
