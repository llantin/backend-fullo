<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    protected $allowedOrigins = [
        'http://localhost:5173',
        'https://frontend-fullo.vercel.app',
        'https://tienda-fullo.vercel.app',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');

        // Solo permitir los orígenes que están en la lista
        if (in_array($origin, $this->allowedOrigins)) {
            $headers = [
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept, Origin',
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Max-Age' => '86400',
            ];

            // Respuesta para peticiones OPTIONS (preflight)
            if ($request->isMethod('OPTIONS')) {
                return response()->json([], 200, $headers);
            }

            // Para peticiones normales
            $response = $next($request);
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }

            return $response;
        }

        // Si el origen no está permitido, continuar sin modificar headers
        return $next($request);
    }
}
