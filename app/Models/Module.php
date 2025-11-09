<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        "name",
        "route",
        "icon",
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, "role_modules");
    }
}
