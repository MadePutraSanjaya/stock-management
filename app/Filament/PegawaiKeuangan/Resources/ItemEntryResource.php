<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Filament\Resources\ItemEntryResource\Pages;
use App\Models\ItemEntry;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ItemEntryResource extends Resource
{
    protected static ?string $model = ItemEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $navigationLabel = 'Barang Masuk';
    protected static ?string $label = 'Barang Masuk';
    protected static ?string $pluralLabel = 'Daftar Barang Masuk';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('item_id')
                ->label('Nama Barang')
                ->relationship('item', 'name')
                ->required(),

            TextInput::make('quantity')
                ->label('Jumlah')
                ->numeric()
                ->required(),

            TextInput::make('supplier')
                ->label('Pemasok')
                ->required(),

            DatePicker::make('entry_date')
                ->label('Tanggal Masuk')
                ->required(),

            Textarea::make('notes')
                ->label('Catatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name')->label('Nama Barang'),
                TextColumn::make('quantity')->label('Jumlah'),
                TextColumn::make('supplier')->label('Pemasok'),
                TextColumn::make('entry_date')->label('Tanggal Masuk')->date('d-m-Y'),
                TextColumn::make('user.nama_lengkap')->label('Dibuat Oleh'),
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

    public static function afterCreate($record): void
    {
        $record->item->increment('stock', $record->quantity);
    }

    public static function afterUpdate($record): void
    {
        $original = $record->getOriginal();
        $diff = $record->quantity - $original['quantity'];

        if ($diff !== 0) {
            $record->item->increment('stock', $diff);
        }
    }

    public static function afterDelete($record): void
    {
        $record->item->decrement('stock', $record->quantity);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemEntries::route('/'),
            'create' => Pages\CreateItemEntry::route('/buat'),
            'edit' => Pages\EditItemEntry::route('/{record}/ubah'),
        ];
    }
}
