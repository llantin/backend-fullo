<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovementsController extends Controller
{
    /**
     * Listar todos los movimientos con relaciones
     */
    public function index()
    {
        $movements = Movement::with(['item', 'user.person', 'receipt', 'receipt_detail'])->get();
        return response()->json([
            'status' => true,
            'movements' => $movements
        ]);
    }
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
