<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('nomor_handphone')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('role')
                    ->options([
                        Role::ADMIN->value => 'Admin',
                        Role::PEGAWAI->value => 'Pegawai',
                        Role::PEGAWAI_KEUANGAN->value => 'Pegawai Keuangan',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('alamat')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('gender')
                    ->options([
                        Gender::LAKI_LAKI->value => 'Laki-laki',
                        Gender::PEREMPUAN->value => 'Perempuan',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('tempat_lahir')
                    ->maxLength(255),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->maxDate(now()),

                Forms\Components\FileUpload::make('foto_profile_path')
                    ->image()
                    ->directory('profile-photos')
                    ->maxSize(1024)
                    ->label('Foto Profil'),

                Forms\Components\Section::make('Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->label('Password'),

                    ])
                    ->collapsible()
                    ->visible(fn(string $context): bool => $context === 'create' || $context === 'edit'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('nomor_handphone'),
                Tables\Columns\TextColumn::make('role'),
                Tables\Columns\TextColumn::make('alamat'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('tempat_lahir'),
                Tables\Columns\TextColumn::make('tanggal_lahir'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
