<?php

/**
 * Configuración CORS (Cross-Origin Resource Sharing)
 *
 * Esta configuración define las políticas de CORS para el backend de Laravel.
 * Permite controlar qué orígenes, métodos y headers están permitidos
 * para solicitudes desde dominios diferentes al del servidor.
 *
 * Documentación: https://laravel.com/docs/cors
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Rutas sujetas a CORS
    |--------------------------------------------------------------------------
    |
    | Define qué rutas estarán sujetas a las políticas CORS.
    | Se usa el patrón 'api/*' para todas las rutas API y
    | 'sanctum/csrf-cookie' para las cookies CSRF de Sanctum.
    |
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Métodos HTTP permitidos
    |--------------------------------------------------------------------------
    |
    | Define los métodos HTTP que se permiten en las solicitudes CORS.
    | '*' permite todos los métodos (GET, POST, PUT, DELETE, etc.).
    |
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Orígenes permitidos
    |--------------------------------------------------------------------------
    |
    | Lista específica de orígenes (dominios) que pueden hacer
    | solicitudes al backend. Incluye:
    | - localhost:5173: Desarrollo local con Vite
    | - inventarios-fullo.vercel.app: Panel de administración
    | - frontend-fullo.vercel.app: Frontend principal
    | - tienda-fullo.vercel.app: Tienda en línea
    | - tienda2-fullo.vercel.app: Segunda tienda en línea
    |
    */
    'allowed_origins' => [
        'http://localhost:5173',
        'https://inventarios-fullo.vercel.app',
        'https://frontend-fullo.vercel.app',
        'https://tienda-fullo.vercel.app',
        'https://tienda2-fullo.vercel.app'
    ],

    /*
    |--------------------------------------------------------------------------
    | Patrones de orígenes permitidos
    |--------------------------------------------------------------------------
    |
    | Permite orígenes usando expresiones regulares.
    | Ejemplo: ['*.example.com'] permitiría cualquier subdominio.
    | Actualmente vacío para mayor control específico.
    |
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Headers permitidos
    |--------------------------------------------------------------------------
    |
    | Define qué headers se permiten en las solicitudes CORS.
    | '*' permite todos los headers necesarios para la API.
    |
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Headers expuestos
    |--------------------------------------------------------------------------
    |
    | Headers que el navegador puede exponer al JavaScript del frontend.
    | Normalmente vacío para APIs RESTful estándar.
    |
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Tiempo de caché de preflight
    |--------------------------------------------------------------------------
    |
    | Cuánto tiempo (en segundos) el navegador puede cachear
    | las respuestas de preflight OPTIONS. 0 = sin caché.
    |
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Soporte para credenciales
    |--------------------------------------------------------------------------
    |
    | Si se permiten cookies, tokens de autorización y otras
    | credenciales en las solicitudes CORS. Necesario para Sanctum.
    |
    */
    'supports_credentials' => true,
];
