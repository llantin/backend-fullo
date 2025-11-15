<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Receipt - Recibos de compra/venta
 *
 * Representa las transacciones principales del sistema, registrando
 * compras y ventas con información del usuario, persona y tipo.
 *
 * @property int $id Identificador único del recibo
 * @property string $receipt_code Código único del recibo
 * @property string|null $description Descripción opcional
 * @property int $user_id ID del usuario que creó el recibo
 * @property int $person_id ID de la persona (proveedor/cliente)
 * @property string $type Tipo: 'Compra' o 'Venta'
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Receipt extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'receipt_code',
        'description',
        'user_id',
        'person_id',
        'type',
    ];

    /**
     * Relación muchos a uno con usuario
     *
     * Cada recibo está asociado al usuario que lo creó.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación muchos a uno con persona
     *
     * Cada recibo está vinculado a una persona (proveedor o cliente).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Person>
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Relación uno a muchos con detalles de recibo
     *
     * Un recibo puede tener múltiples líneas de detalle con productos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ReceiptDetail>
     */
    public function receipt_details()
    {
        return $this->hasMany(ReceiptDetail::class);
    }

    /**
     * Relación uno a muchos con movimientos
     *
     * Un recibo puede generar múltiples movimientos de inventario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Movement>
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
