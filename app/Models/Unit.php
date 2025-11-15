<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Unit - Unidades de medida
 *
 * Define las unidades de medida disponibles en el sistema,
 * clasificadas por tipo (longitud, peso, volumen, etc.).
 *
 * @property int $id Identificador único de la unidad
 * @property string $name Nombre completo de la unidad
 * @property string|null $symbol Símbolo abreviado de la unidad
 * @property string $type Tipo de medida (Longitud, Peso, Volumen, Unitario)
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Unit extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'symbol',
        'type',
    ];
}
