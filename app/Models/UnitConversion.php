<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo UnitConversion - Conversiones entre unidades
 *
 * Define los factores de conversión entre diferentes unidades de medida,
 * permitiendo cálculos precisos en el sistema de inventario.
 *
 * @property int $id Identificador único de la conversión
 * @property string $comercial_unit Unidad comercial (ej: 'Caja', 'Docena')
 * @property string $base_unit Unidad base (ej: 'Unidad', 'Kilogramo')
 * @property float $conversion_factor Factor de conversión numérico
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class UnitConversion extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        "comercial_unit",
        "base_unit",
        "conversion_factor",
    ];
}
