<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'type',
        'identification_type',
        'identification_number',
    ];

    public function receipts(){
        return $this->hasMany(Receipt::class);
    }
    public function user(){
        return $this->hasOne(User::class);
    }
}
