<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicYearResource\Pages;
use App\Filament\Resources\AcademicYearResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Notifications\Notification;
use App\Models\AcademicYear;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;
    protected static ?string $modelLabel = 'Tahun Ajaran'; // Nama buat tombol dan judul
    protected static ?string $pluralModelLabel = 'Data Tahun Ajaran'; // Nama buat menu sidebar
    protected static ?string $navigationLabel = 'Tahun Ajaran'; // Label di navigasi
    protected static ?string $navigationGroup = 'Data Master'; // Grup di navigasi
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Tahun Ajaran')
                    ->placeholder('Contoh: 2023/2024')
                    ->required(),
                Select::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktifkan Periode Ini?')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun'),
                TextColumn::make('semester')
                    ->sortable()
                    ->label('Semester'),
                ToggleColumn::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->defaultSort('name', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // protected function getCreatedNotification(): ?Notification
    // {
    //     return Notification::make()
    //         ->title('Tahun Ajaran Berhasil Dibuat')
    //         ->body('Tahun ajaran baru telah berhasil ditambahkan.')
    //         ->success();
    // }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademicYears::route('/'),
            'create' => Pages\CreateAcademicYear::route('/create'),
            'edit' => Pages\EditAcademicYear::route('/{record}/edit'),
        ];
    }
}
