<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Barang';
    protected static ?string $pluralLabel = 'Daftar Barang';
    protected static ?string $navigationLabel = 'Manajemen Barang';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->label('Nama Barang'),

            Textarea::make('description')
                ->label('Deskripsi'),

            TextInput::make('stock')
                ->numeric()
                ->default(0)
                ->required()
                ->label('Stok'),

            TextInput::make('unit')
                ->label('Satuan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Barang')->searchable(),
                TextColumn::make('stock')->label('Stok'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->date('d-m-Y'),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('Filter Tanggal Dibuat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah'),
            ])
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/buat'),
            'edit' => Pages\EditItem::route('/{record}/ubah'),
        ];
    }
}
