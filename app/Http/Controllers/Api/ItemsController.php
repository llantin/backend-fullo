<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('category')->get();
        return response()->json([
            'status' => true,
            'items' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image_path = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'item_' . time() . '.' . $image->getClientOriginalExtension();

            // Guardar en public/imgs
            $image->move(public_path('imgs'), $filename);
            $image_path = 'imgs/' . $filename; // ruta relativa para almacenar en DB
        }

        $itemData = $request->except('image');
        $itemData['image'] = $image_path;

        $item = Item::create($itemData);
        $item->load(['category']);

        return response()->json([
            'status' => true,
            'message' => "Item created successfully!",
            'item' => $item
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $item->update($request->all());
        $item->load(['category']);
        return response()->json([
            'status' => true,
            'message' => "Item updated successfully!",
            'item' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json([
            'status' => true,
            'message' => "Item deleted successfully!"
        ], 200);
    }
}
