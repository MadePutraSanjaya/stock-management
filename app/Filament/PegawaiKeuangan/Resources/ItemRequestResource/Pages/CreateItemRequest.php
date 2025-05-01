<?php

namespace App\Filament\PegawaiKeuangan\Resources\ItemRequestResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\ItemRequestResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateItemRequest extends CreateRecord
{
    protected static string $resource = ItemRequestResource::class;

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['approved_by'])) {
            $data['approved_by'] = Auth::id();
            $data['approved_at'] = Carbon::now();
        }
    
        return $data;
    }
}
