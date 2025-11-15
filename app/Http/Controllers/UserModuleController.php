<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModule;

/**
 * Controlador de Módulos de Usuario
 *
 * Gestiona las consultas relacionadas con los módulos asignados
 * a diferentes roles de usuario para control de permisos.
 */
class UserModuleController extends Controller
{
    /**
     * Obtener módulos asignados a un rol
     *
     * Devuelve todos los módulos asociados a un rol específico,
     * incluyendo la información detallada de cada módulo.
     *
     * @param int $id ID del rol
     * @return \Illuminate\Http\JsonResponse Lista de módulos con detalles
     */
    public function getModules($id){
        // Obtener módulos asociados al rol con información completa
        $modules = RoleModule::where("role_id", $id)->with("modules")->get();
        return response()->json([
            'status' => true,
            'modules' => $modules
        ]);
    }
}
