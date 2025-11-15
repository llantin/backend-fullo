<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador de Autenticación
 *
 * Maneja todas las operaciones relacionadas con la autenticación de usuarios,
 * incluyendo login, cambio de contraseña y reseteo de contraseñas.
 * Utiliza Laravel Sanctum para la gestión de tokens de API.
 */
class AuthController extends Controller
{
    /**
     * Iniciar sesión de usuario
     *
     * Valida las credenciales del usuario y genera un token de acceso
     * utilizando Laravel Sanctum para autenticación stateless.
     *
     * @param Request $request Datos de la solicitud (username, password)
     * @return \Illuminate\Http\JsonResponse Respuesta con token y datos del usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = User::where('username', $request->username)->with('role', 'person')->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User logged in successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    /**
     * Restablecer contraseña con token
     *
     * Permite a los usuarios restablecer su contraseña utilizando
     * un token enviado por email. Valida el token y actualiza la contraseña.
     *
     * @param Request $request Datos de la solicitud (email, token, password)
     * @return \Illuminate\Http\JsonResponse Respuesta de confirmación
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Buscar registro del token en la tabla password_reset_tokens
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json([
                'status' => false,
                'message' => 'Código inválido o expirado',
            ], 400);
        }

        // Actualizar la contraseña del usuario
        $user = User::whereHas('person', function ($query) use ($request) {
            $query->where('email', $request->email);
        })->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar token después de usarlo para seguridad
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña actualizada correctamente',
        ]);
    }

    /**
     * Cambiar contraseña del usuario autenticado
     *
     * Permite a los usuarios cambiar su contraseña actual por una nueva,
     * requiriendo verificación de la contraseña actual.
     *
     * @param Request $request Datos de la solicitud (currentPassword, newPassword)
     * @return \Illuminate\Http\JsonResponse Respuesta de confirmación
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:6|confirmed', // Confirmación automática usando newPassword_confirmation
        ]);

        $user = Auth::user();

        // Verificar que la contraseña actual sea correcta
        if (!Hash::check($request->currentPassword, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'La contraseña actual es incorrecta',
            ], 400);
        }

        // Actualizar la contraseña
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña actualizada correctamente',
        ]);
    }
}
