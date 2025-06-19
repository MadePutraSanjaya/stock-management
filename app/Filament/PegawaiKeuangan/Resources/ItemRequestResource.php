<?php

namespace App\Filament\PegawaiKeuangan\Resources;

use App\Enums\Status;
use App\Filament\Resources\ItemRequestResource\Pages;
use App\Models\ItemRequest;
use App\Models\User;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ItemRequestResource extends Resource
{
    protected static ?string $model = ItemRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Permintaan Barang';
    protected static ?string $pluralLabel = 'Permintaan Barang';
    protected static ?string $navigationLabel = 'Permintaan Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Hapus hidden field user_id dari form
                // Akan dihandle di mutateFormDataBeforeCreate

                TextInput::make('title')
                    ->required()
                    ->label('Nama Barang'),

                Textarea::make('description')
                    ->required()
                    ->label('Deskripsi'),

                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->label('Jumlah'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Nama Barang'),
                TextColumn::make('quantity')->label('Jumlah'),
                TextColumn::make('created_at')->label('Tanggal Permintaan')->dateTime('d-m-Y H:i'),
                TextColumn::make('user.nama_lengkap')->label('Dibuat Oleh'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Status::APPROVED->value => 'success',
                        Status::REJECTED->value => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('approvedBy.nama_lengkap')
                    ->label('Disetujui Oleh')
                    ->visible(fn($record) => $record && $record->status !== Status::REJECTED->value),
            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('Filter Tanggal Permintaan'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Ubah'),

                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status === Status::PENDING->value)
                        ->action(function ($record) {
                            $record->update([
                                'status' => Status::APPROVED->value,
                                'approved_by' => Filament::auth()->user()->nip, // pakai NIP
                                'approved_at' => now(),
                            ]);
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status === Status::PENDING->value)
                        ->action(function ($record) {
                            $record->update([
                                'status' => Status::REJECTED->value,
                                'approved_by' => Filament::auth()->user()->nip, // pakai NIP
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

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Debug: Lihat data user yang login
        $user = Filament::auth()->user();
        
        // Cari user berdasarkan NIP untuk mendapatkan ID yang benar
        if ($user && isset($user->nip)) {
            $userRecord = User::where('nip', $user->nip)->first();
            if ($userRecord) {
                $data['user_id'] = $userRecord->id;
            } else {
                // Jika tidak ditemukan berdasarkan NIP, coba cari berdasarkan field lain
                // atau throw exception
                throw new \Exception("User dengan NIP {$user->nip} tidak ditemukan di database");
            }
        } else {
            throw new \Exception("User tidak memiliki NIP atau tidak login");
        }

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Untuk update, pastikan user_id tidak berubah jika sudah ada
        if (!isset($data['user_id'])) {
            $user = Filament::auth()->user();
            if ($user && isset($user->nip)) {
                $userRecord = User::where('nip', $user->nip)->first();
                if ($userRecord) {
                    $data['user_id'] = $userRecord->id;
                }
            }
        }

        if (!isset($data['approved_by'])) {
            $user = Filament::auth()->user();
            $data['approved_by'] = $user->nip ?? null;
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