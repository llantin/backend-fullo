<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

/**
 * Controlador API para Unidades de Medida
 *
 * Gestiona las operaciones CRUD para las unidades de medida
 * utilizadas en el sistema de inventario.
 */
class UnitsController extends Controller
{
    /**
     * Listar todas las unidades
     *
     * Devuelve una lista completa de todas las unidades de medida
     * disponibles en el sistema.
     *
     * @return \Illuminate\Http\JsonResponse Lista de unidades
     */
    public function index()
    {
        $units = Unit::all();
        return response()->json([
            'status' => true,
            'units' => $units
        ]);
    }

    /**
     * Crear una nueva unidad
     *
     * Crea una nueva unidad de medida en el sistema.
     *
     * @param Request $request Datos de la nueva unidad
     * @return \Illuminate\Http\JsonResponse Unidad creada
     */
    public function store(Request $request)
    {
        $unit = Unit::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit created successfully!",
            'unit' => $unit
        ], 200);
    }

    /**
     * Actualizar una unidad existente
     *
     * Actualiza los datos de una unidad de medida específica.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param Unit $unit Instancia de la unidad a actualizar
     * @return \Illuminate\Http\JsonResponse Unidad actualizada
     */
    public function update(Request $request, Unit $unit)
    {
        $unit->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit updated successfully!",
            'unit' => $unit
        ], 200);
    }

    /**
     * Eliminar una unidad
     *
     * Elimina una unidad de medida específica del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param Unit $unit Instancia de la unidad a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return response()->json([
            'status' => true,
            'message' => "Unit deleted successfully!"
        ], 200);
    }
}
