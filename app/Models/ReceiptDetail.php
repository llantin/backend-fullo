<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceiptDetail extends Model
{
    protected $fillable = [
        'receipt_id',
        'item_id',
        'quantity',
        'unit',
        'price',
        'subtotal',
    ];

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function movements()
    {
        return $this->hasMany(Movement::class, 'receipt_detail_id');
    }
}
