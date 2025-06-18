<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemWithdrawalResource\Pages;
use App\Models\ItemWithdrawal;
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
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ItemWithdrawalResource extends Resource
{
    protected static ?string $model = ItemWithdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Pengambilan Barang';
    protected static ?string $pluralLabel = 'Daftar Pengambilan Barang';
    protected static ?string $navigationLabel = 'Pengambilan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required()
                    ->label('Nama Barang'),

                TextInput::make('withdrawal_by')
                    ->required()
                    ->label('Diambil Oleh'),

                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->label('Jumlah'),

                DatePicker::make('withdrawal_date')
                    ->required()
                    ->label('Tanggal Pengambilan'),

                Textarea::make('purpose')
                    ->label('Tujuan Pengambilan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name')->label('Nama Barang'),
                TextColumn::make('quantity')->label('Jumlah'),
                TextColumn::make('withdrawal_by')->label('Diambil Oleh'),
                TextColumn::make('withdrawal_date')->label('Tanggal')->date('d-m-Y'),
                TextColumn::make('user.nama_lengkap')->label('Dicatat Oleh'),
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
        $item = $record->item;
        $item->decrement('stock', $record->quantity);
    }

    public static function afterUpdate($record): void
    {
        $original = $record->getOriginal();
        $diff = $record->quantity - $original['quantity'];

        if ($diff !== 0) {
            $record->item->decrement('stock', $diff);
        }
    }

    public static function afterDelete($record): void
    {
        $record->item->increment('stock', $record->quantity);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['taken_by'])) {
            $data['taken_by'] = Auth::id();
        }
        return $data;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemWithdrawals::route('/'),
            'create' => Pages\CreateItemWithdrawal::route('/buat'),
            'edit' => Pages\EditItemWithdrawal::route('/{record}/ubah'),
        ];
    }
}
