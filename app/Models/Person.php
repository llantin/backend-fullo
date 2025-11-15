<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Person - Personas del sistema
 *
 * Representa personas físicas que pueden ser proveedores, clientes
 * o empleados. Sirve como base para crear cuentas de usuario.
 *
 * @property int $id Identificador único de la persona
 * @property string $name Nombre de la persona
 * @property string $last_name Apellido de la persona
 * @property string $email Correo electrónico
 * @property string $phone Número de teléfono
 * @property string $type Tipo: 'Usuario', 'Proveedor', 'Cliente', etc.
 * @property string $identification_type Tipo de documento: 'DNI', 'RUC', etc.
 * @property string $identification_number Número de documento
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Person extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'type',
        'identification_type',
        'identification_number',
    ];

    /**
     * Relación uno a muchos con recibos
     *
     * Una persona puede tener múltiples recibos asociados
     * (como cliente o proveedor).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Receipt>
     */
    public function receipts(){
        return $this->hasMany(Receipt::class);
    }

    /**
     * Relación uno a uno con usuario
     *
     * Una persona puede tener una cuenta de usuario asociada.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<User>
     */
    public function user(){
        return $this->hasOne(User::class);
    }
}
