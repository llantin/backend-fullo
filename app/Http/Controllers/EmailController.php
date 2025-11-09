<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
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

    public function sendPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_link' => 'required|url',
        ]);

        try {
            // Buscar usuario según el email de la persona
            $user = User::whereHas('person', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->with('person')->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'El correo no está registrado'
                ], 404);
            }

            // Generar código de 6 dígitos
            $code = rand(100000, 999999);

            // Guardar en password_resets
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $code, // usamos token como el código
                    'created_at' => now()
                ]
            );

            // Construir el link de restablecimiento
            $finalLink = $request->reset_link
                . '?email=' . urlencode($request->email)
                . '&code=' . $code;

            // Payload para EmailJS
            $payload = [
                'email' => $request->email,
                'token' => $code,
                'link' => $finalLink,
            ];

            // Si tienes nombre y apellido en la persona
            if (isset($user->person->name) && isset($user->person->last_name)) {
                $payload['full_name'] = $user->person->name . ' ' . $user->person->last_name;
            }

            $response = $this->sendEmail(env('EMAILJS_RESET_TEMPLATE_ID'), $payload);

            return $response->successful()
                ? response()->json(['status' => true, 'message' => 'Correo de restablecimiento enviado.'])
                : response()->json(['status' => false, 'error' => $response->body()], 500);

        } catch (\Throwable $e) {
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
