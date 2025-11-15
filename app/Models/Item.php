<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Item - Productos del inventario
 *
 * Representa los productos/ítems que se gestionan en el sistema de inventario.
 * Incluye información detallada de productos, precios, stocks y relaciones.
 *
 * @property int $id Identificador único del ítem
 * @property string $name Nombre del producto
 * @property string $description Descripción detallada
 * @property string $brand Marca del producto
 * @property string $model Modelo del producto
 * @property string $presentation Presentación/empaque
 * @property string $unit_measurement Unidad de medida
 * @property float $price Precio del producto
 * @property int $minimum_stock Stock mínimo para alertas
 * @property int $maximum_stock Stock máximo recomendado
 * @property int $category_id ID de la categoría
 * @property string|null $image Ruta de la imagen del producto
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Item extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'brand',
        'model',
        'presentation',
        'unit_measurement',
        'price',
        'minimum_stock',
        'maximum_stock',
        'category_id',
        'image'
    ];

    /**
     * Relación muchos a uno con categoría
     *
     * Cada ítem pertenece a una categoría específica.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category>
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relación uno a muchos con movimientos
     *
     * Un ítem puede tener múltiples movimientos de inventario (kardex).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Movement>
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
