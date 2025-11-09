<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleModule extends Model
{
    public function modules()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
