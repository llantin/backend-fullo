<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleModule;
use Illuminate\Http\Request;

/**
 * Controlador API para Roles
 *
 * Gestiona las operaciones CRUD para los roles del sistema.
 * Maneja la asignación de módulos/permisos a cada rol.
 */
class RolesController extends Controller
{
    /**
     * Listar todos los roles
     *
     * Devuelve una lista completa de roles con sus módulos asociados.
     *
     * @return \Illuminate\Http\JsonResponse Lista de roles con módulos
     */
    public function index()
    {
        $roles = Role::with('modules')->get();
        return response()->json([
            'status' => true,
            'roles' => $roles
        ]);
    }

    /**
     * Crear un nuevo rol
     *
     * Crea un nuevo rol con validación de datos y asignación opcional
     * de módulos/permisos al rol.
     *
     * @param Request $request Datos del rol (name, description, modules[])
     * @return \Illuminate\Http\JsonResponse Rol creado con módulos asignados
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $role = Role::create($request->all());

        // Asignar módulos si fueron proporcionados
        if ($request->has('modules') && is_array($request->modules)) {
            RoleModule::insert(
                array_map(function ($module) use ($role) {
                    return [
                        'role_id' => $role->id,
                        'module_id' => $module,
                    ];
                }, $request->modules)
            );
        }

        $role->load('modules');

        return response()->json([
            'status' => true,
            'message' => "Role created successfully!",
            'role' => $role
        ], 200);
    }

    /**
     * Actualizar un rol existente
     *
     * Actualiza los datos del rol y sincroniza sus módulos/permisos.
     * Utiliza route model binding y el método sync() para relaciones.
     *
     * @param Request $request Datos actualizados (name, description, modules[])
     * @param Role $role Instancia del rol a actualizar
     * @return \Illuminate\Http\JsonResponse Rol actualizado con módulos
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'modules' => 'array|min:1',
        ]);

        // Actualizar datos básicos del rol
        $role->update($request->only(['name', 'description']));

        // Sincronizar módulos asignados
        if ($request->has('modules') && is_array($request->modules)) {
            $role->modules()->sync($request->modules);
        }

        $role->load('modules');

        return response()->json([
            'status' => true,
            'message' => "Role updated successfully!",
            'role' => $role
        ], 200);
    }

    /**
     * Eliminar un rol
     *
     * Elimina un rol específico del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param Role $role Instancia del rol a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'status' => true,
            'message' => "Role deleted successfully!"
        ], 200);
    }
}
