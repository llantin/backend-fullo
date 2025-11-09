<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    protected $fillable = [
        "comercial_unit",
        "base_unit",
        "conversion_factor",
    ];
}
