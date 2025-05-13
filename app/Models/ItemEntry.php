<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'entry_date',
        'created_by',
        'supplier',
        'notes',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($entry) {
            $entry->item->increment('stock', $entry->quantity);
        });
    
        static::updated(function ($entry) {
            $originalQuantity = $entry->getOriginal('quantity');
            $difference = $entry->quantity - $originalQuantity;
            $entry->item->increment('stock', $difference);
        });
    
        static::deleted(function ($entry) {
            $entry->item->decrement('stock', $entry->quantity);
        });
    }
    

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
