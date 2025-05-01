<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'stock',
        'unit',
    ];
    

    public function entries()
    {
        return $this->hasMany(ItemEntry::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(ItemWithdrawal::class);
    }
}