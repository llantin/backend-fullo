<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function modules()
    {
        return $this->belongsToMany(Module::class, "role_modules");
    }
}
