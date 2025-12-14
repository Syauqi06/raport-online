<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Illuminate\Validation\Rule;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $modelLabel = 'Siswa'; // Nama buat tombol dan judul
    protected static ?string $pluralModelLabel = 'Data Siswa'; // Nama buat menu sidebar
    protected static ?string $navigationLabel = 'Siswa'; // Label di navigasi
    protected static ?string $navigationGroup = 'Data Master'; // Grup di navigasi
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Akun Login')
                    ->description('Masukkan data untuk login siswa')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->formatStateUsing(fn ($record) => $record?->user?->name)
                            ->required()
                            ->rule(function ($record) {
                                if ($record) {
                                    return Rule::unique('users', 'name')->ignore($record->user_id);
                                }
                                return 'unique:users,name';
                            }),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->formatStateUsing(fn ($record) => $record?->user?->email)
                            ->rule(function ($record) {
                                if ($record) {
                                    return Rule::unique('users', 'email')->ignore($record->user_id);
                                }
                                return 'unique:users,email';
                            }),
                        TextInput::make('password')
                            ->password()
                            ->required(fn ($livewire) => $livewire instanceof Pages\CreateStudent) // Hanya wajib saat membuat data 
                            ->dehydrated(fn ($state) => filled($state)) // Jika kosong maka jangan kirim ke backend
                            ->label('Password'),
                    ]),
                Section::make('Data Siswa')
                    ->schema([
                        TextInput::make('nisn')
                            ->label('NISN')
                            ->numeric()
                            ->unique(ignoreRecord: true),
                        TextInput::make('nis')
                            ->label('NIS')
                            ->numeric()
                            ->unique(ignoreRecord: true),
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir'),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir'),
                        TextInput::make('phone')
                            ->tel()
                            ->label('No. Telepon'),
                        Textarea::make('address')
                            ->label('Alamat')
                            ->autosize(),
                        Select::make('classroom_id')
                            ->label('Kelas')
                            ->relationship('classroom', 'name') // Relasi ke tabel Classrooms
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email'),
                TextColumn::make('nisn')
                    ->label('NISN')
                    ->sortable(),
                TextColumn::make('nis')
                    ->label('NIS')
                    ->sortable(),
                TextColumn::make('birth_place')
                    ->label('Tempat Lahir'),
                TextColumn::make('birth_date')
                    ->label('Tanggal Lahir'),
                TextColumn::make('phone')
                    ->label('No. Telepon'),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
    
}
