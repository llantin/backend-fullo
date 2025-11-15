<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controlador API para Ítems/Productos
 *
 * Gestiona las operaciones CRUD para los productos del inventario.
 * Incluye manejo de imágenes para los productos y carga de relaciones.
 */
class ItemsController extends Controller
{
    /**
     * Listar todos los ítems
     *
     * Devuelve una lista completa de todos los productos
     * con información de su categoría asociada.
     *
     * @return \Illuminate\Http\JsonResponse Lista de ítems con categorías
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
     * Crear un nuevo ítem
     *
     * Crea un nuevo producto en el inventario. Maneja la subida
     * opcional de imágenes que se almacenan en public/imgs/.
     *
     * @param Request $request Datos del nuevo ítem
     * @return \Illuminate\Http\JsonResponse Ítem creado con categoría
     */
    public function store(Request $request)
    {
        $image_path = null;

        // Procesar imagen si fue enviada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'item_' . time() . '.' . $image->getClientOriginalExtension();

            // Guardar imagen en directorio público
            $image->move(public_path('imgs'), $filename);
            $image_path = 'imgs/' . $filename; // Ruta relativa para BD
        }

        // Preparar datos del ítem
        $itemData = $request->except('image');
        $itemData['image'] = $image_path;

        $item = Item::create($itemData);
        $item->load(['category']); // Cargar relación de categoría

        return response()->json([
            'status' => true,
            'message' => "Item created successfully!",
            'item' => $item
        ], 200);
    }

    /**
     * Actualizar un ítem existente
     *
     * Actualiza los datos de un producto específico. Permite cambiar
     * la imagen manteniendo la existente si no se proporciona nueva.
     *
     * @param Request $request Datos actualizados
     * @param Item $item Instancia del ítem a actualizar
     * @return \Illuminate\Http\JsonResponse Ítem actualizado con categoría
     */
    public function update(Request $request, Item $item)
    {
        $image_path = $item->image; // Mantener imagen existente por defecto

        // Procesar nueva imagen si fue enviada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'item_' . time() . '.' . $image->getClientOriginalExtension();

            // Guardar nueva imagen
            $image->move(public_path('imgs'), $filename);
            $image_path = 'imgs/' . $filename; // Nueva ruta relativa
        }

        // Preparar datos actualizados
        $itemData = $request->except('image');
        $itemData['image'] = $image_path;

        $item->update($itemData);
        $item->load(['category']); // Recargar relación de categoría

        return response()->json([
            'status' => true,
            'message' => "Item updated successfully!",
            'item' => $item
        ], 200);
    }

    /**
     * Eliminar un ítem
     *
     * Elimina un producto específico del inventario.
     * Utiliza route model binding para inyección automática.
     *
     * @param Item $item Instancia del ítem a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
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
