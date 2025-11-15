<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Controlador API para Categorías
 *
 * Gestiona las operaciones CRUD para las categorías de productos
 * en el sistema de inventario. Proporciona endpoints RESTful
 * para listar, crear, actualizar y eliminar categorías.
 */
class CategoriesController extends Controller
{
    /**
     * Listar todas las categorías
     *
     * Devuelve una lista completa de todas las categorías
     * disponibles en el sistema.
     *
     * @return \Illuminate\Http\JsonResponse Lista de categorías
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Crear una nueva categoría
     *
     * Valida y crea una nueva categoría en el sistema.
     * Requiere nombre obligatorio y descripción opcional.
     *
     * @param Request $request Datos de la nueva categoría
     * @return \Illuminate\Http\JsonResponse Categoría creada
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'status' => true,
            'message' => "Category created successfully!",
            'category' => $category
        ], 201);
    }

    /**
     * Actualizar una categoría existente
     *
     * Valida y actualiza los datos de una categoría específica.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param Category $category Instancia de la categoría a actualizar
     * @return \Illuminate\Http\JsonResponse Categoría actualizada
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json([
            'status' => true,
            'message' => "Category updated successfully!",
            'category' => $category
        ]);
    }

    /**
     * Eliminar una categoría
     *
     * Elimina una categoría específica del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param Category $category Instancia de la categoría a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => "Category deleted successfully!"
        ]);
    }
}
