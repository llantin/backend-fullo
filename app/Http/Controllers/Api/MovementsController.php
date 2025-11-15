<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controlador API para Movimientos de Inventario
 *
 * Gestiona las operaciones CRUD para los movimientos de inventario (kardex).
 * Incluye consultas con todas las relaciones cargadas.
 */
class MovementsController extends Controller
{
    /**
     * Listar todos los movimientos con relaciones
     *
     * Devuelve una lista completa de movimientos con información
     * del ítem, usuario, recibo y detalle del recibo asociados.
     *
     * @return \Illuminate\Http\JsonResponse Lista de movimientos con relaciones
     */
    public function index()
    {
        $movements = Movement::with(['item', 'user.person', 'receipt', 'receipt_detail'])->get();
        return response()->json([
            'status' => true,
            'movements' => $movements
        ]);
    }

    /**
     * Actualizar un movimiento existente
     *
     * Actualiza los datos de un movimiento específico.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param Movement $movement Instancia del movimiento a actualizar
     * @return \Illuminate\Http\JsonResponse Movimiento actualizado
     */
    public function update(Request $request, Movement $movement)
    {
        $movement->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Movement updated successfully!",
            'movement' => $movement
        ], 200);
    }

    /**
     * Eliminar un movimiento
     *
     * Elimina un movimiento específico del kardex.
     * Incluye validación de existencia del movimiento.
     *
     * @param int $id ID del movimiento a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy($id)
    {
        $movement = Movement::find($id);
        if (!$movement) {
            return response()->json(['message' => 'Movimiento no encontrado'], 404);
        }

        $movement->delete();

        return response()->json(['message' => 'Movimiento eliminado'], 200);
    }
}
