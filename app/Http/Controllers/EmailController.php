<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Controlador de Correos Electrónicos
 *
 * Maneja el envío de correos electrónicos utilizando EmailJS como servicio externo.
 * Incluye funcionalidades para soporte técnico y restablecimiento de contraseñas.
 */
class EmailController extends Controller
{
    /**
     * Enviar email genérico utilizando EmailJS
     *
     * Método privado que encapsula la lógica de envío de emails
     * a través del servicio EmailJS.
     *
     * @param string $templateId ID del template de EmailJS
     * @param array $params Parámetros para el template
     * @return \Illuminate\Http\Client\Response Respuesta de EmailJS
     */
    private function sendEmail($templateId, $params)
    {
        return Http::withHeaders([
            'origin' => config('app.url', 'http://localhost:3000'),
            'Content-Type' => 'application/json',
        ])->post('https://api.emailjs.com/api/v1.0/email/send', [
            'service_id' => env('EMAILJS_SERVICE_ID'),
            'template_id' => $templateId,
            'user_id' => env('EMAILJS_PUBLIC_KEY'),
            'template_params' => $params,
        ]);
    }

    /**
     * Enviar mensaje de soporte técnico
     *
     * Permite a los usuarios enviar mensajes de soporte técnico
     * que son enviados por email al equipo de soporte.
     *
     * @param Request $request Datos del mensaje (name, subject, description)
     * @return \Illuminate\Http\JsonResponse Respuesta de confirmación
     */
    public function sendSupport(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        $response = $this->sendEmail(env('EMAILJS_SUPPORT_TEMPLATE_ID'), [
            'full_name' => $request->name,
            'reason' => $request->subject,
            'detail' => $request->description,
        ]);

        return $response->successful()
            ? response()->json(['status' => true, 'message' => 'Soporte enviado.'])
            : response()->json(['status' => false, 'error' => $response->body()], 500);
    }

    /**
     * Enviar email de restablecimiento de contraseña
     *
     * Genera un código de 6 dígitos, lo guarda en la base de datos
     * y envía un email con el enlace de restablecimiento.
     *
     * @param Request $request Datos de la solicitud (email, reset_link)
     * @return \Illuminate\Http\JsonResponse Respuesta de confirmación
     */
    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_link' => 'required|url',
        ]);

        try {
            // Buscar usuario según el email de la persona asociada
            $user = User::whereHas('person', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->with('person')->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'El correo no está registrado'
                ], 404);
            }

            // Generar código de 6 dígitos para verificación
            $code = rand(100000, 999999);

            // Guardar código en tabla de tokens de reseteo
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $code, // Usamos el campo token para almacenar el código
                    'created_at' => now()
                ]
            );

            // Construir enlace completo de restablecimiento
            $finalLink = $request->reset_link
                . '?email=' . urlencode($request->email)
                . '&code=' . $code;

            // Preparar payload para EmailJS
            $payload = [
                'email' => $request->email,
                'token' => $code,
                'link' => $finalLink,
            ];

            // Incluir nombre completo si está disponible
            if (isset($user->person->name) && isset($user->person->last_name)) {
                $payload['full_name'] = $user->person->name . ' ' . $user->person->last_name;
            }

            $response = $this->sendEmail(env('EMAILJS_RESET_TEMPLATE_ID'), $payload);

            return $response->successful()
                ? response()->json(['status' => true, 'message' => 'Correo de restablecimiento enviado.'])
                : response()->json(['status' => false, 'error' => $response->body()], 500);

        } catch (\Throwable $e) {
            // Registrar error en logs para debugging
            Log::error('Error en sendPasswordReset: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }
}
