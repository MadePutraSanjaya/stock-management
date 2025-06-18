<?php

namespace App\Filament\Pegawai\Resources;

use App\Enums\Role;
use App\Enums\Status;
use App\Filament\Pegawai\Resources\ItemRequestResource\Pages;
use App\Models\ItemRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ItemRequestResource extends Resource
{
    protected static ?string $model = ItemRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Permintaan Barang';
    protected static ?string $label = 'Permintaan';
    protected static ?string $pluralLabel = 'Permintaan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(function () {
                        $pegawai = User::where('role', Role::PEGAWAI->value)->first();
                        return $pegawai ? $pegawai->id : null;
                    }),

                TextInput::make('title')
                    ->label('Judul')
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required(),

                TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul'),
                TextColumn::make('created_at')->label('Tanggal Dibuat'),
                TextColumn::make('quantity')->label('Jumlah'),
                TextColumn::make('user.role')->label('Dibuat Oleh'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Status::APPROVED->value => 'success',
                        Status::REJECTED->value => 'warning',
                        default => 'warning',
                    }),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('Tanggal Dibuat'),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemRequests::route('/'),
            'create' => Pages\CreateItemRequest::route('/create'),
            'edit' => Pages\EditItemRequest::route('/{record}/edit'),
        ];
    }
}
