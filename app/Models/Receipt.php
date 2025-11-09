<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = [
        'receipt_code',
        'description',
        'user_id',
        'person_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
    public function receipt_details()
    {
        return $this->hasMany(ReceiptDetail::class);
    }
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
