<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\Pages;
use App\Filament\PegawaiKeuangan\Resources\WithdrawItemResource\RelationManagers;
use App\Models\ItemWithdrawal;
use App\Models\WithdrawItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WithdrawItemResource extends Resource
{
    protected static ?string $model = ItemWithdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                Forms\Components\DatePicker::make('withdrawal_date')
                    ->required(),
                Forms\Components\Textarea::make('purpose'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name')->label('Item'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('withdrawal_date')->date(),
                Tables\Columns\TextColumn::make('user.nama_lengkap')->label('Taken By'),
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
        return [
            //
        ];
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
