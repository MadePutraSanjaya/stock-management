<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemRequestResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemRequestResource;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateItemRequest extends CreateRecord
{
    protected static string $resource = ItemRequestResource::class;

  
}
