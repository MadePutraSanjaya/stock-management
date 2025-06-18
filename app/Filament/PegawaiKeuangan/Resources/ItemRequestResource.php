<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Enums\Status;
use App\Filament\Resources\ItemRequestResource\Pages;
use App\Models\ItemRequest;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ItemRequestResource extends Resource
{
    protected static ?string $model = ItemRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Permintaan Barang';
    protected static ?string $label = 'Permintaan Barang';
    protected static ?string $pluralLabel = 'Daftar Permintaan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')->default(fn () => Auth::id()),

                TextInput::make('title')
                    ->label('Judul Permintaan')
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi Permintaan')
                    ->required(),

                TextInput::make('quantity')
                    ->label('Jumlah Barang')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul'),
                TextColumn::make('quantity')->label('Jumlah'),
                TextColumn::make('created_at')->label('Tanggal Permintaan')->date('d-m-Y'),
                TextColumn::make('user.nama_lengkap')->label('Dibuat Oleh'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Status::APPROVED->value => 'success',
                        Status::REJECTED->value => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('Filter Tanggal Dibuat'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Ubah'),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === Status::PENDING->value)
                        ->action(function ($record) {
                            $record->update([
                                'status' => Status::APPROVED->value,
                                'approved_by' => Auth::id(),
                                'approved_at' => now(),
                            ]);
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->status === Status::PENDING->value)
                        ->action(function ($record) {
                            $record->update([
                                'status' => Status::REJECTED->value,
                                'approved_by' => Auth::id(),
                                'approved_at' => now(),
                            ]);
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['approved_by'])) {
            $data['approved_by'] = Auth::id();
            $data['approved_at'] = Carbon::now();
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
            'index' => Pages\ListItemRequests::route('/'),
            'create' => Pages\CreateItemRequest::route('/buat'),
            'edit' => Pages\EditItemRequest::route('/{record}/ubah'),
        ];
    }
}
