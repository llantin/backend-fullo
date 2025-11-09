<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'receipt_id',
        'receipt_detail_id',
        'quantity',
        'type',
        'price',
        'stock',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
    public function receipt_detail()
    {
        return $this->belongsTo(ReceiptDetail::class);
    }
}
