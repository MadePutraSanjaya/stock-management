<?php

namespace App\Filament\PegawaiKeuangan\Resources\UserResource\Pages;

use App\Filament\PegawaiKeuangan\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
