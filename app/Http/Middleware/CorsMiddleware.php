<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware personalizado para manejo de CORS (Cross-Origin Resource Sharing)
 *
 * Este middleware implementa manualmente el manejo de CORS para controlar
 * qué orígenes pueden acceder a la API. Complementa la configuración
 * automática de Laravel con control más granular.
 *
 * Funcionalidades:
 * - Validación de orígenes permitidos
 * - Manejo de preflight requests (OPTIONS)
 * - Configuración de headers CORS apropiados
 * - Soporte para credenciales (cookies, tokens)
 *
 * @see https://developer.mozilla.org/es/docs/Web/HTTP/CORS
 */
class CorsMiddleware
{
    /**
     * Lista de orígenes permitidos para solicitudes CORS
     *
     * Incluye dominios de desarrollo y producción:
     * - localhost:5173: Entorno de desarrollo con Vite
     * - inventarios-fullo.vercel.app: Panel de administración de inventarios
     * - frontend-fullo.vercel.app: Frontend principal de la aplicación
     * - tienda-fullo.vercel.app: Primera tienda en línea
     * - tienda2-fullo.vercel.app: Segunda tienda en línea
     *
     * @var array<string>
     */
    protected $allowedOrigins = [
        'http://localhost:5173',
        'https://inventarios-fullo.vercel.app',
        'https://frontend-fullo.vercel.app',
        'https://tienda-fullo.vercel.app',
        'https://tienda2-fullo.vercel.app'
    ];

    /**
     * Manejar la solicitud HTTP y aplicar políticas CORS
     *
     * @param Request $request La solicitud HTTP entrante
     * @param Closure $next El siguiente middleware en la cadena
     * @return Response La respuesta HTTP modificada
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el origen de la solicitud
        $origin = $request->headers->get('Origin');

        // Verificar si el origen está en la lista de permitidos
        if (in_array($origin, $this->allowedOrigins)) {
            // Headers CORS para orígenes permitidos
            $headers = [
                'Access-Control-Allow-Origin' => $origin,           // Origen específico permitido
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, OPTIONS', // Métodos permitidos
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept, Origin', // Headers permitidos
                'Access-Control-Allow-Credentials' => 'true',       // Permitir credenciales
                'Access-Control-Max-Age' => '86400',               // Cache preflight por 24 horas
            ];

            // Manejar solicitudes preflight OPTIONS
            if ($request->isMethod('OPTIONS')) {
                return response()->json([], 200, $headers);
            }

            // Para solicitudes normales, agregar headers a la respuesta
            $response = $next($request);
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }

            return $response;
        }

        // Si el origen no está permitido, continuar sin modificar headers
        // Esto permite que otras políticas de CORS (como la configuración global) tomen efecto
        return $next($request);
    }
}
