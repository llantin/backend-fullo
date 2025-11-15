<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo RoleModule - Relación entre roles y módulos
 *
 * Tabla pivote que establece las relaciones many-to-many entre roles y módulos,
 * permitiendo asignar permisos específicos a cada rol del sistema.
 *
 * @property int $id Identificador único de la relación
 * @property int $role_id ID del rol
 * @property int $module_id ID del módulo
 * @property \Carbon\Carbon $created_at Fecha de creación
 * @property \Carbon\Carbon $updated_at Fecha de última actualización
 */
class RoleModule extends Model
{
    /**
     * Relación muchos a uno con módulo
     *
     * Cada relación apunta a un módulo específico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Module>
     */
    public function modules()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
