<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingResource\Pages;
use App\Filament\Resources\TeachingResource\RelationManagers;
use App\Models\Teaching;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeachingResource extends Resource
{
    protected static ?string $model = Teaching::class;
    protected static ?string $modelLabel = 'Pengajaran'; // Nama buat tombol dan judul
    protected static ?string $pluralModelLabel = 'Data Pengajaran'; // Nama buat menu sidebar
    protected static ?string $navigationLabel = 'Pengajaran'; // Label di navigasi
    protected static ?string $navigationGroup = 'Data Master'; // Grup di navigasi

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('teacher_id') // Kolom untuk memilih guru
                    ->label('Guru Pengajar')
                    ->relationship('teacher', 'id') // Relasi ke Teacher
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name) // Menampilkan nama guru dari relasi User
                    ->searchable()
                    ->preload() // Memuat data sebelumnya untuk performa lebih baik
                    ->required(),
                Select::make('subject_id') // Kolom untuk memilih mata pelajaran
                    ->label('Mata Pelajaran')
                    ->relationship('subject', 'name') // Relasi ke Subject
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('classroom_id') // Kolom untuk memilih kelas
                    ->label('Kelas')
                    ->relationship('classroom', 'name') // Relasi ke Classroom
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.user.name') // Kolom untuk menampilkan nama guru harus akses melalui relasi
                    ->label('Guru')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subject.name') // Kolom untuk menampilkan nama mata pelajaran
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('classroom.name') // Kolom untuk menampilkan nama kelas
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
            ])
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
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachings::route('/'),
            'create' => Pages\CreateTeaching::route('/create'),
            'edit' => Pages\EditTeaching::route('/{record}/edit'),
        ];
    }
}
