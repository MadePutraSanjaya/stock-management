<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\Pages;
use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\RelationManagers;
use App\Models\ItemWithdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class WithdrawItemResource extends Resource
{
    protected static ?string $model = ItemWithdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pengambilan Barang';
    protected static ?string $label = 'Pengambilan';
    protected static ?string $pluralLabel = 'Pengambilan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->label('Barang')
                    ->required(),
                Forms\Components\TextInput::make('withdrawal_by')
                    ->label('Diambil Oleh')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('withdrawal_date')
                    ->label('Tanggal Pengambilan')
                    ->required(),
                Forms\Components\Textarea::make('purpose')
                    ->label('Keperluan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')->label('Barang'),
                Tables\Columns\TextColumn::make('quantity')->label('Jumlah'),
                Tables\Columns\TextColumn::make('withdrawal_date')->date()->label('Tanggal Pengambilan'),
                Tables\Columns\TextColumn::make('withdrawal_by')->label('Diambil Oleh'),
                Tables\Columns\TextColumn::make('user.nama_lengkap')->label('Dicatat Oleh'),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('Tanggal Dibuat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit'),
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
            'index' => Pages\ListWithdrawItems::route('/'),
            'create' => Pages\CreateWithdrawItem::route('/create'),
            'edit' => Pages\EditWithdrawItem::route('/{record}/edit'),
        ];
    }
}
