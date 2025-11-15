<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

/**
 * Seeder para poblar la tabla de categorías con datos extensos.
 *
 * Crea un catálogo completo de categorías de productos para ferretería,
 * incluyendo herramientas, materiales de construcción, eléctricos, etc.
 * Genera aproximadamente 150 categorías organizadas por especialidad.
 */
class MoreCategories extends Seeder
{
    /**
     * Ejecutar el seeder de categorías.
     *
     * Crea categorías organizadas en grupos principales:
     * - Herramientas (manuales y eléctricas)
     * - Pinturas y acabados
     * - Materiales eléctricos
     * - Fontanería y plomería
     * - Tornillería y fijaciones
     * - Adhesivos y selladores
     * - Seguridad industrial
     * - Construcción y albañilería
     * - Jardinería
     * - Iluminación
     * - Cerrajería
     * - Revestimientos y pisos
     * - Baños y grifería
     * - Cocina y gas
     * - Estructuras metálicas
     * - Maderas y tableros
     * - Puertas y ventanas
     * - Selladores y pinturas especiales
     *
     * Más 130 categorías adicionales específicas de ferretería.
     * Total: ~150 categorías con nombres y descripciones detalladas.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Herramientas manuales', 'description' => 'Martillos, destornilladores, llaves, alicates y herramientas sin motor.'],
            ['name' => 'Herramientas eléctricas', 'description' => 'Taladros, esmeriles, sierras eléctricas y demás herramientas motorizadas.'],
            ['name' => 'Pinturas y acabados', 'description' => 'Pinturas, esmaltes, barnices, rodillos, brochas y accesorios.'],
            ['name' => 'Material eléctrico', 'description' => 'Cables, interruptores, tomacorrientes, focos y material eléctrico en general.'],
            ['name' => 'Fontanería y plomería', 'description' => 'Tuberías, válvulas, grifos, codos, adaptadores y accesorios de agua.'],
            ['name' => 'Tornillería y fijaciones', 'description' => 'Tornillos, clavos, pernos, tuercas, arandelas y fijaciones especiales.'],
            ['name' => 'Adhesivos y selladores', 'description' => 'Silicones, pegamentos, cintas adhesivas y espumas expansivas.'],
            ['name' => 'Seguridad industrial', 'description' => 'Guantes, cascos, gafas, botas, mascarillas y equipo de protección.'],
            ['name' => 'Ferretería general', 'description' => 'Artículos diversos de ferretería para uso general.'],
            ['name' => 'Construcción y albañilería', 'description' => 'Palas, picos, carretillas, niveles, mezcladoras y herramientas de obra.'],
            ['name' => 'Jardinería', 'description' => 'Palas, tijeras, mangueras, aspersores y productos para jardín.'],
            ['name' => 'Iluminación', 'description' => 'Bombillos LED, lámparas, reflectores y sistemas de iluminación.'],
            ['name' => 'Cerrajería', 'description' => 'Candados, cerraduras, llaves, bisagras y accesorios de puertas.'],
            ['name' => 'Revestimientos y pisos', 'description' => 'Cerámicos, porcelanatos, pegamentos y accesorios para pisos.'],
            ['name' => 'Baños y grifería', 'description' => 'Sanitarios, lavamanos, duchas, grifos y accesorios de baño.'],
            ['name' => 'Cocina y gas', 'description' => 'Accesorios de gas, llaves, tubos, conexiones y válvulas.'],
            ['name' => 'Estructuras metálicas', 'description' => 'Perfiles, ángulos, platinas y materiales metálicos.'],
            ['name' => 'Maderas y tableros', 'description' => 'Maderas, MDF, aglomerados y tableros OSB.'],
            ['name' => 'Puertas y ventanas', 'description' => 'Puertas, marcos, bisagras y cerraduras.'],
            ['name' => 'Selladores y pinturas especiales', 'description' => 'Pinturas epóxicas, anticorrosivas y selladores industriales.'],
            // 👇 continúa con más categorías...
        ];

        // Añadimos el resto (del 21 al 150)
        $extras = [
            'Cables y conectores',
            'Accesorios para pintura',
            'Equipos de soldadura',
            'Accesorios de soldadura',
            'Lijas y abrasivos',
            'Tuberías PVC',
            'Tuberías galvanizadas',
            'Accesorios para baño',
            'Pegamentos industriales',
            'Cierres y pasadores',
            'Bisagras y soportes',
            'Brocas y puntas',
            'Llaves combinadas',
            'Sierras y serruchos',
            'Discos de corte',
            'Discos de desbaste',
            'Cepillos metálicos',
            'Mangueras y accesorios',
            'Rodillos y brochas',
            'Escaleras y andamios',
            'Clavos y grapas',
            'Pernos y tuercas',
            'Arandelas y anclajes',
            'Accesorios eléctricos',
            'Tableros eléctricos',
            'Canaletas y ductos',
            'Extensiones eléctricas',
            'Tomas y enchufes',
            'Interruptores y timbres',
            'Cinta aislante',
            'Focos LED',
            'Reflectores industriales',
            'Sensores de movimiento',
            'Alarmas y timbres',
            'Candados y cerraduras',
            'Cadenas y ganchos',
            'Poleas y tensores',
            'Ruedas y rodamientos',
            'Grifos de cocina',
            'Válvulas de paso',
            'Mangueras de gas',
            'Llaves de gas',
            'Teflones y selladores',
            'Tubos de cobre',
            'Tubos de hierro',
            'Uniones y codos',
            'Codos PVC',
            'Tapones y adaptadores',
            'Fijaciones químicas',
            'Espumas expansivas',
            'Cintas teflón',
            'Sargentos y prensas',
            'Niveles y escuadras',
            'Flexómetros y reglas',
            'Carretillas y palas',
            'Picos y zapapicos',
            'Mazos y martillos',
            'Alicates y pinzas',
            'Destornilladores de precisión',
            'Llaves ajustables',
            'Cuchillos y cúteres',
            'Sargentos tipo C',
            'Sargentos rápidos',
            'Taladros eléctricos',
            'Rotomartillos',
            'Esmeriles angulares',
            'Pulidoras',
            'Sierras circulares',
            'Caladoras',
            'Lijadoras orbitales',
            'Compresores de aire',
            'Clavadoras neumáticas',
            'Engrapadoras neumáticas',
            'Pistolas de pintura',
            'Motosierras',
            'Podadoras',
            'Sopladoras',
            'Cortasetos',
            'Riego y aspersión',
            'Macetas y jardineras',
            'Abonos y fertilizantes',
            'Guantes de trabajo',
            'Cascos de seguridad',
            'Lentes de protección',
            'Botas industriales',
            'Mascarillas y respiradores',
            'Arneses de seguridad',
            'Señalización industrial',
            'Cintas de peligro',
            'Extintores',
            'Botiquines',
            'Cadenas de seguridad',
            'Cables de acero',
            'Accesorios para techos',
            'Canaletas pluviales',
            'Tejas metálicas',
            'Tejas plásticas',
            'Impermeabilizantes',
            'Selladores acrílicos',
            'Aditivos para concreto',
            'Cementos y cales',
            'Arena y grava',
            'Rejillas y tapas',
            'Mallas y cercas',
            'Clavos de concreto',
            'Pernos de anclaje',
            'Anclajes químicos',
            'Remaches',
            'Abrazaderas',
            'Soportes metálicos',
            'Tuberías flexibles',
            'Tuberías de presión',
            'Válvulas check',
            'Válvulas bola',
            'Válvulas mariposa',
            'Tanques de agua',
            'Bombas de agua',
            'Filtros y purificadores',
            'Accesorios de fontanería',
            'Cierres automáticos',
            'Puertas metálicas',
            'Ventanas de aluminio',
            'Persianas y cortinas',
            'Tableros MDF',
            'Melaminas decorativas',
            'Clavos para madera',
            'Pegamentos para madera',
            'Lacas y selladores',
            'Bisagras ocultas',
            'Cierres magnéticos',
            'Correderas y rieles',
            'Manijas y tiradores',
            'Cadenas decorativas',
            'Rejillas decorativas',
            'Accesorios de mobiliario',
        ];

        foreach ($extras as $extra) {
            $categories[] = [
                'name' => $extra,
                'description' => 'Artículos relacionados con ' . strtolower($extra) . '.',
            ];
        }

        Category::insert($categories);
    }
}

