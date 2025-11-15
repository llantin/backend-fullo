<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo ReceiptDetail - Detalles de recibos
 *
 * Representa las líneas de detalle de un recibo, especificando
 * qué productos, cantidades y precios están incluidos.
 *
 * @property int $id Identificador único del detalle
 * @property int $receipt_id ID del recibo al que pertenece
 * @property int $item_id ID del ítem/producto
 * @property float $quantity Cantidad del producto
 * @property string|null $unit Unidad de medida
 * @property float $price Precio unitario
 * @property float $subtotal Subtotal (cantidad × precio)
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class ReceiptDetail extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'receipt_id',
        'item_id',
        'quantity',
        'unit',
        'price',
        'subtotal',
    ];

    /**
     * Relación muchos a uno con recibo
     *
     * Cada detalle pertenece a un recibo específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Receipt>
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * Relación muchos a uno con ítem
     *
     * Cada detalle está asociado a un producto específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Item>
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relación uno a muchos con movimientos
     *
     * Un detalle de recibo puede generar movimientos de inventario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Movement>
     */
    public function movements()
    {
        return $this->hasMany(Movement::class, 'receipt_detail_id');
    }
}
