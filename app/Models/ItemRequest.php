<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Facades\Filament;

class ItemRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'quantity',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->user_id || !User::find($model->user_id)) {
                $currentUser = Filament::auth()->user();
                
                if ($currentUser && isset($currentUser->nip)) {
                    $userRecord = User::where('nip', $currentUser->nip)->first();
                    
                    if ($userRecord) {
                        $model->user_id = $userRecord->id;
                    } else {
                        throw new \Exception("User dengan NIP {$currentUser->nip} tidak ditemukan");
                    }
                } else {
                    throw new \Exception("Tidak dapat menentukan user yang login");
                }
            }
        });

        static::updating(function ($model) {
            if (!$model->user_id || !User::find($model->user_id)) {
                $currentUser = Filament::auth()->user();
                
                if ($currentUser && isset($currentUser->nip)) {
                    $userRecord = User::where('nip', $currentUser->nip)->first();
                    
                    if ($userRecord) {
                        $model->user_id = $userRecord->id;
                    }
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'nip');
    }
}