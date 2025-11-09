<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController2 extends Controller
{
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
