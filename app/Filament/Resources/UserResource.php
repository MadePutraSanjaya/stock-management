<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages;
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

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $label = 'Pengguna';
    protected static ?string $pluralLabel = 'Daftar Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\TextInput::make('nomor_handphone')
                    ->label('Nomor HP')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\TextInput::make('nip')
                    ->label('NIP')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\Select::make('role')
                    ->label('Peran')
                    ->options([
                        Role::ADMIN->value => 'Admin',
                        Role::PEGAWAI->value => 'Pegawai',
                        Role::PEGAWAI_KEUANGAN->value => 'Pegawai Keuangan',
                    ])
                    ->required()
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        Gender::LAKI_LAKI->value => 'Laki-laki',
                        Gender::PEREMPUAN->value => 'Perempuan',
                    ])
                    ->required()
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->maxLength(255)
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->maxDate(now())
                    ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),

                Forms\Components\Section::make('Kata Sandi')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->maxLength(255)
                            ->disabled(fn($record) => self::isAdminEditingOtherUser($record)),
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
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('nomor_handphone')->label('Nomor HP'),
                Tables\Columns\TextColumn::make('role')->label('Peran'),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat'),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                Tables\Columns\TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->date('d-m-Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->visible(fn($record) => self::canEditUser($record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->visible(fn() => !self::isCurrentUserAdmin()),
                ]),
            ]);
    }

    /**
     * Cek apakah user yang login adalah admin dan sedang mengedit user lain
     */
    protected static function isAdminEditingOtherUser($record): bool
    {
        if (!$record) return false; // Jika tidak ada record (create mode), return false
        
        $currentUser = Auth::user();
        
        // Jika user yang login adalah admin dan record yang diedit bukan dirinya sendiri
        return $currentUser->role === Role::ADMIN->value && $currentUser->id !== $record->id;
    }

    /**
     * Cek apakah user yang login bisa mengedit user tertentu
     */
    protected static function canEditUser($record): bool
    {
        $currentUser = Auth::user();
        
        // Admin hanya bisa edit dirinya sendiri
        if ($currentUser->role === Role::ADMIN->value) {
            return $currentUser->id === $record->id;
        }
        
        // Role lain bisa edit semua (sesuai permission yang sudah ada)
        return true;
    }

    /**
     * Cek apakah user yang login adalah admin
     */
    protected static function isCurrentUserAdmin(): bool
    {
        return Auth::user()->role === Role::ADMIN->value;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/buat'),
            'edit' => Pages\EditUser::route('/{record}/ubah'),
        ];
    }
}