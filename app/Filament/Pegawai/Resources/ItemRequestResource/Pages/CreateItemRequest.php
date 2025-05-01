<?php

namespace App\Filament\Pegawai\Resources\ItemRequestResource\Pages;

use App\Filament\Pegawai\Resources\ItemRequestResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateItemRequest extends CreateRecord
{
    protected static string $resource = ItemRequestResource::class;
    
}
