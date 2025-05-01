<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Filament\Resources\ItemEntryResource\Pages;
use App\Filament\Resources\ItemEntryResource\RelationManagers;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemEntryResource extends Resource
{
    protected static ?string $model = ItemEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('item_id')->relationship('item', 'name')->required(),
            TextInput::make('quantity')->numeric()->required(),
         
            DatePicker::make('entry_date')->required(),
            Textarea::make('notes'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.name'),
                TextColumn::make('quantity'),
              
                TextColumn::make('entry_date')->date(),
                TextColumn::make('user.nama_lengkap')->label('Created By'),
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
        $item->increment('stock', $record->quantity);
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItemEntries::route('/'),
            'create' => Pages\CreateItemEntry::route('/create'),
            'edit' => Pages\EditItemEntry::route('/{record}/edit'),
        ];
    }
}
