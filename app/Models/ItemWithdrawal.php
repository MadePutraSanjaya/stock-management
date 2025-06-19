<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;

class ItemWithdrawal extends Model
{
    protected $fillable = [
        'item_id',
        'quantity',
        'withdrawal_date',
        'withdrawal_by',
        'purpose',
        'taken_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->taken_by || !User::find($model->taken_by)) {
                $currentUser = Filament::auth()->user();

                if ($currentUser && isset($currentUser->nip)) {
                    $userRecord = \App\Models\User::where('nip', $currentUser->nip)->first();

                    if ($userRecord) {
                        $model->taken_by = $userRecord->id;
                    } else {
                        throw new \Exception("User dengan NIP {$currentUser->nip} tidak ditemukan");
                    }
                } else {
                    throw new \Exception("Tidak dapat menentukan user yang login");
                }
            }
        });

        static::created(function ($withdrawal) {
            $withdrawal->item->decrement('stock', $withdrawal->quantity);
        });

        static::updated(function ($withdrawal) {
            if ($withdrawal->wasChanged('quantity') || $withdrawal->wasChanged('item_id')) {
                $originalItem = Item::find($withdrawal->getOriginal('item_id'));
                if ($originalItem) {
                    $originalItem->increment('stock', $withdrawal->getOriginal('quantity'));
                }
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
