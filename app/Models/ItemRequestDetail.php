<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequestDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_request_id',
        'item_id',
        'quantity',
        'notes',
    ];

    public function itemRequest()
    {
        return $this->belongsTo(ItemRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}