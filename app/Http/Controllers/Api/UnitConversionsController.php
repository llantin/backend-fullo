<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UnitConversion;

/**
 * Controlador API para Conversiones de Unidades
 *
 * Gestiona las operaciones CRUD para las conversiones entre unidades
 * de medida y consultas específicas de conversiones.
 */
class UnitConversionsController extends Controller
{
    /**
     * Listar todas las conversiones de unidades
     *
     * Devuelve una lista completa de todas las conversiones
     * de unidades disponibles en el sistema.
     *
     * @return \Illuminate\Http\JsonResponse Lista de conversiones
     */
    public function index()
    {
        $unit_conversions = UnitConversion::all();
        return response()->json([
            'status' => true,
            'unit_conversions' => $unit_conversions
        ]);
    }

    /**
     * Crear una nueva conversión de unidades
     *
     * Crea una nueva conversión entre unidades comerciales y base.
     *
     * @param Request $request Datos de la nueva conversión
     * @return \Illuminate\Http\JsonResponse Conversión creada
     */
    public function store(Request $request)
    {
        $unit_conversion = UnitConversion::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit conversion created successfully!",
            'unit_conversion' => $unit_conversion
        ], 200);
    }

    /**
     * Actualizar una conversión existente
     *
     * Actualiza los datos de una conversión de unidades específica.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param UnitConversion $unit_conversion Instancia a actualizar
     * @return \Illuminate\Http\JsonResponse Conversión actualizada
     */
    public function update(Request $request, UnitConversion $unit_conversion)
    {
        $unit_conversion->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Unit conversion updated successfully!",
            'unit_conversion' => $unit_conversion
        ], 200);
    }

    /**
     * Eliminar una conversión de unidades
     *
     * Elimina una conversión específica del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param UnitConversion $unit_conversion Instancia a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(UnitConversion $unit_conversion)
    {
        $unit_conversion->delete();
        return response()->json([
            'status' => true,
            'message' => "Unit conversion deleted successfully!"
        ], 200);
    }

    /**
     * Obtener conversiones para una unidad base
     *
     * Devuelve todas las conversiones disponibles para una unidad base específica.
     * Útil para mostrar opciones de conversión en interfaces.
     *
     * @param string $base_unit Unidad base para buscar conversiones
     * @return \Illuminate\Http\JsonResponse Lista de conversiones para la unidad base
     */
    public function getUnits($base_unit)
    {
        $units = UnitConversion::where('base_unit', $base_unit)->get();
        return response()->json([
            'status' => true,
            'units' => $units
        ]);
    }
}
