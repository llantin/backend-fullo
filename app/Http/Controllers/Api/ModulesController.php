<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

/**
 * Controlador API para Módulos del Sistema
 *
 * Gestiona las operaciones CRUD para los módulos/funcionalidades
 * disponibles en el sistema de permisos.
 */
class ModulesController extends Controller
{
    /**
     * Listar todos los módulos
     *
     * Devuelve una lista completa de todos los módulos del sistema.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse Lista de módulos
     */
    public function index(Request $request)
    {
        $modules = Module::all();
        return response()->json([
            'status' => true,
            'modules' => $modules
        ]);
    }

    /**
     * Crear un nuevo módulo
     *
     * Crea un nuevo módulo en el sistema con los datos proporcionados.
     *
     * @param Request $request Datos del nuevo módulo
     * @return \Illuminate\Http\JsonResponse Módulo creado
     */
    public function store(Request $request)
    {
        $module = Module::create($request->all());
        return response()->json([
            'status' => true,
            'message' => "Module created successfully!",
            'module' => $module
        ], 200);
    }

    /**
     * Actualizar un módulo existente
     *
     * Actualiza los datos de un módulo específico.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param Module $module Instancia del módulo a actualizar
     * @return \Illuminate\Http\JsonResponse Módulo actualizado
     */
    public function update(Request $request, Module $module)
    {
        $module->update($request->all());
        return response()->json([
            'status' => true,
            'message' => "Module updated successfully!",
            'module' => $module
        ], 200);
    }

    /**
     * Eliminar un módulo
     *
     * Elimina un módulo específico del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param Module $module Instancia del módulo a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(Module $module)
    {
        $module->delete();
        return response()->json([
            'status' => true,
            'message' => "Module deleted successfully!"
        ], 200);
    }
}
