<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Category - Categorías de productos
 *
 * Representa las categorías que clasifican los productos en el sistema
 * de inventario. Cada categoría puede contener múltiples ítems/productos.
 *
 * @property int $id Identificador único de la categoría
 * @property string $name Nombre de la categoría
 * @property string $description Descripción detallada de la categoría
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Category extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relación uno a muchos con ítems
     *
     * Una categoría puede tener múltiples productos asociados.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Item>
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
