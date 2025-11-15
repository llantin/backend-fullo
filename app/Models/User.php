<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo User - Usuarios del sistema
 *
 * Extiende la clase Authenticatable de Laravel para proporcionar
 * autenticación completa con Sanctum para APIs.
 *
 * @property int $id Identificador único del usuario
 * @property int $person_id ID de la persona asociada
 * @property string $username Nombre de usuario para login
 * @property string $password Contraseña hasheada
 * @property int $role_id ID del rol del usuario
 * @property string|null $remember_token Token para "recordar sesión"
 * @property \Carbon\Carbon|null $email_verified_at Fecha de verificación de email
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        'person_id',
        'username',
        'password',
        'role_id',
    ];

    /**
     * Campos que deben estar ocultos en la serialización
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de atributos
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación muchos a uno con rol
     *
     * Cada usuario pertenece a un rol específico que define sus permisos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Role>
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación muchos a uno con persona
     *
     * Cada usuario está vinculado a una persona con información personal.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Person>
     */
    public function person()
    {
        return $this->belongsTo(Person::class, "person_id");
    }

    /**
     * Relación uno a muchos con movimientos
     *
     * Un usuario puede haber realizado múltiples movimientos de inventario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Movement>
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
