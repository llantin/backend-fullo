<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
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
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Buscar registro del token (aquí asumimos que tienes una tabla `password_reset_tokens` con email, token y created_at)
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

        // Opcional: borrar token después de usarlo
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña actualizada correctamente',
        ]);
    }
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
