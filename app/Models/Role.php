<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Role - Roles de usuario
 *
 * Define los diferentes roles que pueden tener los usuarios en el sistema,
 * con permisos asociados a través de módulos.
 *
 * @property int $id Identificador único del rol
 * @property string $name Nombre del rol
 * @property string $description Descripción de los permisos del rol
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Role extends Model
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
     * Relación muchos a muchos con usuarios
     *
     * Un rol puede estar asignado a múltiples usuarios,
     * y un usuario puede tener múltiples roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User>
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Relación muchos a muchos con módulos
     *
     * Un rol puede tener acceso a múltiples módulos,
     * y un módulo puede estar disponible para múltiples roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Module>
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, "role_modules");
    }
}
