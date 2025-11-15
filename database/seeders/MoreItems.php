<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

/**
 * Seeder para poblar la tabla de ítems/productos con datos realistas.
 *
 * Crea un catálogo de productos de ferretería con información completa,
 * incluyendo precios, stocks, marcas, modelos y asignación automática
 * de imágenes basada en similitud de nombres.
 */
class MoreItems extends Seeder
{
    /**
     * Ejecutar el seeder de ítems.
     *
     * Proceso:
     * 1. Define listas de unidades, marcas y presentaciones
     * 2. Obtiene todas las categorías existentes
     * 3. Escanea el directorio public/imgs/ para encontrar imágenes disponibles
     * 4. Crea 50 productos reales de ferretería con datos completos
     *
     * Características de los productos creados:
     * - Nombres realistas de productos de ferretería
     * - Descripciones detalladas con marca
     * - Modelos generados aleatoriamente
     * - Precios entre 1.50 y 1500.00
     * - Stocks mínimo (5-15) y máximo (30-150)
     * - Unidades de medida variadas
     * - Asignación automática de imágenes por similitud de nombres
     * - Categorías asignadas aleatoriamente
     *
     * Imágenes: Busca coincidencias entre nombre del producto normalizado
     * y nombres de archivos de imagen en public/imgs/
     */
    public function run()
    {
        $faker = \Faker\Factory::create('es_ES');

        $unitMeasurements = [
            'CM',
            'GL',
            'G',
            'KG',
            'LB',
            'L',
            'M',
            'M3',
            'ML',
            'OZ',
            'IN',
            'UND'
        ];

        $brands = [
            'Stanley',
            'Bosch',
            'Makita',
            'DeWalt',
            'Truper',
            'Irwin',
            'Black & Decker',
            'Hilti',
            'Sika',
            '3M',
            'Tesa',
            'Klein Tools',
            'Karcher',
            'Philips',
            'Ledvance',
            'Caterpillar',
            'Ridgid',
            'Total',
            'Einhell',
            'Generico'
        ];

        $presentations = [
            'Unidad',
            'Caja',
            'Blíster',
            'Set',
            'Paquete',
            'Galón',
            'Litro',
            'Bolsa',
            'Tambor',
            'Frasco'
        ];

        $categories = Category::pluck('id')->toArray();

        // 📂 Obtener todas las imágenes disponibles en public/imgs/
        $imagePath = public_path('imgs');
        $images = [];

        if (File::exists($imagePath)) {
            // Solo archivos válidos de imagen
            $images = collect(File::files($imagePath))
                ->filter(fn($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                ->map(fn($file) => 'imgs/' . $file->getFilename()) // ruta relativa a /public
                ->values()
                ->toArray();
        }

        // 🔩 50 productos reales de ferretería
        $realItems = [
            'Taladro percutor 1/2"',
            'Sierra circular 7-1/4"',
            'Lijadora orbital 1/4"',
            'Pulidora angular 4.5"',
            'Martillo de bola 16 oz',
            'Destornillador plano 6"',
            'Destornillador de cruz Phillips #2',
            'Llave inglesa ajustable 10"',
            'Llave combinada 14 mm',
            'Cinta métrica 5 metros',
            'Nivel de burbuja 60 cm',
            'Cúter profesional metálico',
            'Broca para concreto 6 mm',
            'Broca para metal 10 mm',
            'Juego de brocas mixtas 15 pzas',
            'Alicate universal 8"',
            'Pinza de presión 10"',
            'Tornillo drywall 1¼"',
            'Clavo de acero 2"',
            'Cemento Portland tipo I 50 kg',
            'Arena fina m3',
            'Grava gruesa m3',
            'Pintura látex blanco 1 gl',
            'Silicona transparente 280 ml',
            'Sellador acrílico blanco 300 ml',
            'Cinta teflón ½"',
            'Lija para madera grano 120',
            'Disco de corte metal 4.5"',
            'Disco de desbaste concreto 4.5"',
            'Guantes de carnaza',
            'Casco de seguridad',
            'Lentes de protección',
            'Mascarilla N95',
            'Cinta aislante 18 mm',
            'Extensión eléctrica 5 m',
            'Foco LED 12W',
            'Interruptor simple 10A',
            'Tomacorriente doble 10A',
            'Tubo PVC ½" 3 m',
            'Codo PVC ½"',
            'T de PVC ½"',
            'Válvula de bola ½"',
            'Grifo metálico ½"',
            'Manguera de jardín 15 m',
            'Escalera de aluminio 6 peldaños',
            'Carretilla metálica 90 L',
            'Candado de seguridad 50 mm',
            'Bisagra de acero 3"',
            'Cerradura de pomo metálica',
            'Soplete de gas butano portátil'
        ];

        $items = [];

        foreach ($realItems as $name) {
            $category_id = $faker->randomElement($categories);
            $brand = $faker->randomElement($brands);

            // 🖼 Normalizar nombre del producto
            $normalizedName = strtolower(Str::slug($name, ''));

            // 🔍 Buscar imagen que contenga el nombre normalizado
            $matchingImage = collect($images)->first(function ($img) use ($normalizedName) {
                $imageName = strtolower(pathinfo($img, PATHINFO_FILENAME));
                return Str::contains($imageName, $normalizedName);
            });

            $items[] = [
                'name' => $name,
                'description' => "Producto de ferretería: {$name}. Fabricado por {$brand}, ideal para uso profesional o doméstico.",
                'brand' => $brand,
                'model' => strtoupper(Str::random(3)) . '-' . $faker->numberBetween(100, 999),
                'presentation' => $faker->randomElement($presentations),
                'unit_measurement' => $faker->randomElement($unitMeasurements),
                'price' => $faker->randomFloat(2, 1.50, 1500.00),
                'minimum_stock' => $faker->numberBetween(5, 15),
                'maximum_stock' => $faker->numberBetween(30, 150),
                'category_id' => $category_id,
                'image' => $matchingImage ?? null, // 👈 imagen por similitud
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Item::insert($items);
    }
}
