<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemWithdrawal extends Model
{
    protected $fillable = [
        'item_id',
        'quantity',
        'withdrawal_date',
        'purpose',
        'taken_by',
    ];

    protected static function booted()
    {
        static::created(function ($withdrawal) {
            $withdrawal->item->decrement('stock', $withdrawal->quantity);
        });

        static::updated(function ($withdrawal) {
            if ($withdrawal->wasChanged('quantity') || $withdrawal->wasChanged('item_id')) {
                $originalItem = Item::find($withdrawal->getOriginal('item_id'));
                $originalItem->increment('stock', $withdrawal->getOriginal('quantity'));

                $withdrawal->item->decrement('stock', $withdrawal->quantity);
            }
        });

        static::deleted(function ($withdrawal) {
            $withdrawal->item->increment('stock', $withdrawal->quantity);
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }
}
