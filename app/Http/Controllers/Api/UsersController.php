<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Controlador API para Usuarios
 *
 * Gestiona las operaciones CRUD para los usuarios del sistema.
 * Incluye carga de relaciones con roles y personas.
 */
class UsersController extends Controller
{
    /**
     * Listar todos los usuarios
     *
     * Devuelve una lista completa de usuarios con información
     * de su rol y datos personales asociados.
     *
     * @return \Illuminate\Http\JsonResponse Lista de usuarios con relaciones
     */
    public function index()
    {
        $users = User::with('role', 'person')->get();
        return response()->json([
            'status' => true,
            'users' => $users
        ]);
    }

    /**
     * Crear un nuevo usuario
     *
     * Crea un nuevo usuario en el sistema con los datos proporcionados.
     * Carga las relaciones de persona y rol para la respuesta.
     *
     * @param Request $request Datos del nuevo usuario
     * @return \Illuminate\Http\JsonResponse Usuario creado con relaciones
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        $user->load(['person', 'role']);
        return response()->json([
            'status' => true,
            'message' => "User created successfully!",
            'user' => $user
        ], 200);
    }

    /**
     * Actualizar un usuario existente
     *
     * Actualiza los datos de un usuario específico.
     * Utiliza route model binding para inyección automática.
     *
     * @param Request $request Datos actualizados
     * @param User $user Instancia del usuario a actualizar
     * @return \Illuminate\Http\JsonResponse Usuario actualizado con relaciones
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        $user->load(['person', 'role']);
        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'user' => $user
        ], 200);
    }

    /**
     * Eliminar un usuario
     *
     * Elimina un usuario específico del sistema.
     * Utiliza route model binding para inyección automática.
     *
     * @param User $user Instancia del usuario a eliminar
     * @return \Illuminate\Http\JsonResponse Confirmación de eliminación
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully!'
        ], 200);
    }
}
