<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Movement - Movimientos de inventario (Kardex)
 *
 * Registra todos los movimientos de entrada y salida de productos,
 * manteniendo un historial completo del inventario (kardex).
 *
 * @property int $id Identificador único del movimiento
 * @property int $item_id ID del ítem/producto
 * @property int $user_id ID del usuario que realizó el movimiento
 * @property int $receipt_id ID del recibo asociado
 * @property int $receipt_detail_id ID del detalle del recibo
 * @property float $quantity Cantidad del movimiento
 * @property string $type Tipo: 'Compra' o 'Venta'
 * @property float $price Precio unitario del movimiento
 * @property float $stock Stock resultante después del movimiento
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Movement extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'receipt_id',
        'receipt_detail_id',
        'quantity',
        'type',
        'price',
        'stock',
    ];

    /**
     * Relación muchos a uno con ítem
     *
     * Cada movimiento pertenece a un ítem específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Item>
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relación muchos a uno con usuario
     *
     * Cada movimiento está asociado al usuario que lo realizó.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación muchos a uno con recibo
     *
     * Cada movimiento está vinculado a un recibo de compra/venta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Receipt>
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * Relación muchos a uno con detalle de recibo
     *
     * Cada movimiento corresponde a un detalle específico del recibo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<ReceiptDetail>
     */
    public function receipt_detail()
    {
        return $this->belongsTo(ReceiptDetail::class);
    }
}
