<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Enums\Gender;
use App\Enums\Role;
use App\Filament\PegawaiKeuangan\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $label = 'Profil';
    protected static ?string $pluralLabel = 'Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('nomor_handphone')
                    ->label('Nomor Handphone')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Select::make('role')
                    ->label('Peran')
                    ->options([
                        Role::ADMIN->value => 'Admin',
                        Role::PEGAWAI->value => 'Pegawai',
                        Role::PEGAWAI_KEUANGAN->value => 'Pegawai Keuangan',
                    ])
                    ->required()
                    ->visible(fn() => Auth::user()?->role === Role::ADMIN->value),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        Gender::LAKI_LAKI->value => 'Laki-laki',
                        Gender::PEREMPUAN->value => 'Perempuan',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->maxLength(255),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->maxDate(now()),

                Forms\Components\Section::make('Password')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Kata Sandi')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255),
                    ])
                    ->collapsible()
                    ->visible(fn(string $context): bool => $context === 'create' || $context === 'edit'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('nip', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('nomor_handphone')->label('Nomor HP'),
                Tables\Columns\TextColumn::make('role')->label('Peran'),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat'),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                Tables\Columns\TextColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/ubah'),
        ];
    }
}
