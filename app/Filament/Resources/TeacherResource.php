<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;
    protected static ?string $modelLabel = 'Guru'; // Nama buat tombol dan judul
    protected static ?string $pluralModelLabel = 'Data Guru'; // Nama buat menu sidebar
    protected static ?string $navigationLabel = 'Guru'; // Label di navigasi
    protected static ?string $navigationGroup = 'Data Master'; // Grup di navigasi
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Akun Login')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nama Guru'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(table: 'users', ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->required(fn ($operation) => $operation === 'create'), // Hanya wajib saat membuat data
                    ]),

                Section::make('Data Profil Guru')
                    ->schema([
                        TextInput::make('nip')
                            ->numeric()
                            ->required()
                            ->label('NIP')
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone')
                            ->tel()
                            ->label('No. Telepon'),
                        Textarea::make('address')
                            ->label('Alamat'),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama'),
                TextColumn::make('nip')
                    ->label('NIP'),
                TextColumn::make('user.email')
                    ->label('Email'),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }    
}
