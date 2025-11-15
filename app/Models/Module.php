<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Module - Módulos del sistema
 *
 * Representa los diferentes módulos/funcionalidades disponibles en el sistema.
 * Se utiliza para control de permisos basado en roles de usuario.
 *
 * @property int $id Identificador único del módulo
 * @property string $name Nombre del módulo
 * @property string $route Ruta asociada al módulo
 * @property string|null $icon Ícono para la interfaz de usuario
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class Module extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     *
     * @var array<string>
     */
    protected $fillable = [
        "name",
        "route",
        "icon",
    ];

    /**
     * Relación muchos a muchos con roles
     *
     * Un módulo puede estar asignado a múltiples roles,
     * y un rol puede tener acceso a múltiples módulos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Role>
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_modules");
    }
}
