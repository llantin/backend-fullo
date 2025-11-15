<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Delivery - Entregas y envíos
 *
 * Gestiona las entregas de productos, ya sea a domicilio o recogida en tienda.
 * Incluye información de ubicación, estado de entrega y seguimiento.
 *
 * @property int $id Identificador único de la entrega
 * @property int $fk_persona ID de la persona que recibe la entrega
 * @property int $fk_comprobante ID del recibo/comprobante asociado
 * @property string|null $direccion Dirección de entrega
 * @property string|null $referencia Referencia de ubicación
 * @property float|null $latitud Coordenada de latitud GPS
 * @property float|null $longitud Coordenada de longitud GPS
 * @property string $tipo_entrega Tipo: 'delivery' o 'tienda'
 * @property string $estado Estado: 'E'(Enviado), 'R'(Recibido), 'C'(Cancelado)
 * @property \Carbon\Carbon|null $fecha_programada Fecha y hora programada
 * @property string|null $observaciones Notas adicionales
 * @property string|null $hashOrden Hash único para identificar la orden
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Delivery extends Model
{
    use HasFactory;

    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'fk_persona',
        'fk_comprobante',
        'direccion',
        'referencia',
        'latitud',
        'longitud',
        'tipo_entrega',
        'estado',
        'fecha_programada',
        'observaciones',
        'hashOrden', // Hash único para identificar órdenes de pago
    ];

    /**
     * Relación muchos a uno con persona
     *
     * Cada entrega pertenece a una persona (cliente).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Person>
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'fk_persona');
    }

    /**
     * Relación muchos a uno con recibo
     *
     * Cada entrega está asociada a un recibo de venta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Receipt>
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'fk_comprobante');
    }
}
