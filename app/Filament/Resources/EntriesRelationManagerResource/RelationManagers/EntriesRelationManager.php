<?php

namespace App\Filament\Resources\ItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class EntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'entries';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'Barang Masuk';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('entry_date')
                ->label('Tanggal Masuk')
                ->required()
                ->default(now()),
            Forms\Components\TextInput::make('quantity')
                ->label('Jumlah')
                ->numeric()
                ->required()
                ->minValue(1),
            Forms\Components\TextInput::make('price')
                ->label('Harga Satuan')
                ->numeric()
                ->required()
                ->prefix('Rp'),
            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->maxLength(65535),

        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('entry_date')
                ->label('Tanggal Masuk')
                ->date('d M Y')
                ->sortable(),
            Tables\Columns\TextColumn::make('quantity')
                ->label('Jumlah'),
            Tables\Columns\TextColumn::make('price')
                ->label('Harga Satuan')
                ->money('idr'),
            Tables\Columns\TextColumn::make('user.name')
                ->label('Dibuat Oleh'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d M Y, H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function ($data) {
                        $item = $this->getOwnerRecord();
                        $item->stock += $data['quantity'];
                        $item->save();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($data, $record) {
                        $item = $this->getOwnerRecord();
                        $item->stock -= $record->quantity;
                        $item->save();
                    })
                    ->after(function ($data) {
                        $item = $this->getOwnerRecord();
                        $item->stock += $data['quantity'];
                        $item->save();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        $item = $this->getOwnerRecord();
                        $item->stock -= $record->quantity;
                        $item->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        $item = $this->getOwnerRecord();
                        foreach ($records as $record) {
                            $item->stock -= $record->quantity;
                        }
                        $item->save();
                    }),
            ]);
    }
}
