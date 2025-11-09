<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_persona',
        'fk_comprobante',
        'direccion',
        'referencia',
        'latitud',
        'longitud',
        'tipo_entrega',
        'estado',
        'fecha_programada',
        'observaciones',
        'hashOrden', // <-- nuevo
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'fk_persona');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'fk_comprobante');
    }
}
