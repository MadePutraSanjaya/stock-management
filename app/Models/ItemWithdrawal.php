<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'withdrawal_date',
        'taken_by',
        'purpose',
    ];

    protected $casts = [
        'withdrawal_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }
}