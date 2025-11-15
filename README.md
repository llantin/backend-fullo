# Backend de Gestión de Inventario

Este es el backend de una aplicación de gestión de inventario desarrollada con Laravel 12. El sistema permite administrar productos, categorías, usuarios, roles, movimientos de inventario, recibos, unidades de medida y conversiones, además de proporcionar estadísticas de dashboard y funcionalidades de pago en línea.

## Características Principales

- **Gestión de Inventario**: Control de stock, movimientos (entradas/salidas), kardex por producto.
- **Catálogo de Productos**: Administración de ítems con categorías, unidades y conversiones.
- **Usuarios y Roles**: Sistema de autenticación con roles y módulos permisivos.
- **Recibos y Compras**: Manejo de recibos de compra con detalles.
- **Dashboard**: Estadísticas, gráficos y reportes de movimientos.
- **Pagos en Línea**: Integración con checkout inteligente para compras.
- **Exportación**: Reportes de inventario y kardex en formato Excel.
- **API RESTful**: Endpoints completos para integración con frontend.
- **Autenticación**: Uso de Laravel Sanctum para tokens de API.
- **Correos Electrónicos**: Envío de soporte y reseteo de contraseñas.

## Tecnologías Utilizadas

- **Laravel 12**: Framework PHP para el backend.
- **PHP 8.2**: Versión de PHP utilizada.
- **MySQL/PostgreSQL/SQLite**: Bases de datos soportadas.
- **Laravel Sanctum**: Para autenticación de API.
- **PHPSpreadsheet**: Para exportación de reportes Excel.
- **Node.js y NPM**: Para compilación de assets frontend.
- **Docker**: Contenedorización para despliegue fácil.

## Instalación

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js y NPM
- Base de datos (MySQL, PostgreSQL o SQLite)

### Instalación Local

1. Clona el repositorio:
   ```bash
   git clone <url-del-repositorio>
   cd backend
   ```

2. Instala las dependencias de PHP:
   ```bash
   composer install
   ```

3. Instala las dependencias de Node.js:
   ```bash
   npm install
   ```

4. Copia el archivo de configuración de entorno:
   ```bash
   cp .env.example .env
   ```

5. Genera la clave de aplicación:
   ```bash
   php artisan key:generate
   ```

6. Configura la base de datos en `.env` y ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```

7. Ejecuta los seeders para datos iniciales:
   ```bash
   php artisan db:seed
   ```

8. Compila los assets:
   ```bash
   npm run build
   ```

9. Inicia el servidor:
   ```bash
   php artisan serve
   ```

### Uso de Docker

El proyecto incluye un `Dockerfile` para facilitar el despliegue en contenedores.

#### Construcción de la Imagen

```bash
docker build -t backend-inventario .
```

#### Ejecución del Contenedor

```bash
docker run -p 80:80 backend-inventario
```

El contenedor expone el puerto 80 y ejecuta Apache con PHP 8.2. Incluye todas las dependencias necesarias, instala Composer y Node.js, compila los assets, configura permisos y optimiza el rendimiento para producción.

#### Detalles del Dockerfile

- **Base**: PHP 8.2 con Apache.
- **Dependencias del Sistema**: Git, Curl, librerías para imágenes, XML, ZIP, bases de datos.
- **Extensiones PHP**: PDO para MySQL/PostgreSQL/SQLite, MBString, Exif, PCNTL, BCMath, GD, ZIP.
- **Composer**: Instalado para gestión de dependencias PHP.
- **Node.js y NPM**: Para compilación de assets.
- **Configuración Apache**: DocumentRoot en `/public`, habilitado rewrite y overrides.
- **Optimizaciones**: Caché de configuración, rutas y vistas para producción.
- **Permisos**: Configurados para `www-data` en storage y bootstrap/cache.

## Uso de la API

La API está protegida con autenticación Sanctum. Los endpoints principales incluyen:

- **Autenticación**: `/login`, `/password/change`, `/password/reset`
- **Ítems**: `/api/items` (CRUD con stock)
- **Categorías**: `/api/categories`
- **Inventario**: `/api/inventory`, `/export-inventory`
- **Movimientos**: `/api/movements`, `/export-kardex`
- **Dashboard**: `/api/dashboard/stats`, `/dashboard/chart-stats`
- **Checkout**: `/api/checkout` para pagos en línea

Para más detalles, consulta las rutas en `routes/api.php`.

## Contribución

Si deseas contribuir, por favor revisa las guías de contribución de Laravel.

## Licencia

Este proyecto está bajo la licencia MIT.
