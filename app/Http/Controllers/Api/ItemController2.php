<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador API alternativo para Ítems con Información de Stock
 *
 * Versión especializada del controlador de ítems que incluye
 * el stock actual en las respuestas, optimizada para consultas
 * que requieren información de inventario en tiempo real.
 */
class ItemController2 extends Controller
{
    /**
     * Listar todos los ítems con stock actual
     *
     * Devuelve una lista completa de productos incluyendo su stock
     * actual calculado desde el último movimiento registrado.
     * Optimizado para interfaces que requieren stock en tiempo real.
     *
     * @return \Illuminate\Http\JsonResponse Lista de ítems con stock actual
     */
    public function indexWithStock()
    {
        try {
            $items = Item::with('category')
                ->with(['movements' => function ($query) {
                    $query->orderBy('id', 'desc')->limit(1);
                }])
                ->get()
                ->map(function ($item) {
                    $item->current_stock = optional($item->movements->first())->stock ?? 0;
                    unset($item->movements);
                    return $item;
                });

            return response()->json([
                'status' => true,
                'items' => $items
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en indexWithStock: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mostrar un ítem específico con stock actual
     *
     * Devuelve los detalles de un producto específico incluyendo
     * su stock actual calculado desde el último movimiento.
     *
     * @param int $id ID del ítem a consultar
     * @return \Illuminate\Http\JsonResponse Ítem con stock actual o error 404
     */
    public function showWithStock($id)
    {
        try {
            $item = Item::with('category')
                ->with(['movements' => function ($query) {
                    $query->orderBy('id', 'desc')->limit(1);
                }])
                ->find($id);

            if (!$item) {
                return response()->json([
                    'status' => false,
                    'error' => 'Item no encontrado'
                ], 404);
            }

            $item->current_stock = optional($item->movements->first())->stock ?? 0;
            unset($item->movements);

            return response()->json([
                'status' => true,
                'item' => $item
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en showWithStock: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
