<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Filament\Resources\GradeResource\RelationManagers;
use App\Models\Grade;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use App\Models\Teacher;

class GradeResource extends Resource
{
    protected static ?string $model = Grade::class;
    protected static ?string $modelLabel = 'Penilaian'; // Nama buat tombol dan judul
    protected static ?string $pluralModelLabel = 'Data Penilaian'; // Nama buat menu sidebar
    protected static ?string $navigationLabel = 'Penilaian'; // Label di navigasi
    protected static ?string $navigationGroup = 'Data Akademik'; // Grup di navigasi
    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('teaching_id')
                    ->label('Kelas & Mapel')
                    ->relationship('teaching', 'id') // Relasi ke Teaching
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->subject->name} - {$record->classroom->name}") // Menampilkan mata pelajaran dan kelas dari relasi Teaching
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'id') // Relasi ke Student
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name) // Menampilkan nama siswa dari relasi User
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label('Jenis Nilai')
                    ->options([
                        'TUGAS' => 'Tugas',
                        'UH' => 'Ulangan Harian',
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                    ])
                    ->required(),
                TextInput::make('score')
                    ->label('Nilai')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->required(),
                Textarea::make('description')
                    ->label('Catatan Guru'),
                Toggle::make('is_locked')
                    ->label('Kunci Nilai?')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name') // Kolom untuk menampilkan nama siswa harus akses melalui relasi User
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('teaching.subject.name') // Kolom untuk menampilkan nama mata pelajaran harus akses melalui relasi Teaching
                    ->label('Mata Pelajaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('teaching.classroom.name') // Kolom untuk menampilkan nama kelas harus akses melalui relasi Teaching
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Jenis Nilai')
                    ->badge(),
                TextColumn::make('score')
                    ->label('Nilai')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_locked')
                    ->label('Terkunci')
                    ->boolean(),
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
            'index' => Pages\ListGrades::route('/'),
            'create' => Pages\CreateGrade::route('/create'),
            'edit' => Pages\EditGrade::route('/{record}/edit'),
        ];
    }
    
    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Pastikan user sudah login sebelum cek role
        if (!$user) {
            return false;
        }

        return $user->hasRole('admin') || $user->hasRole('guru');
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Pastikan user sudah login sebelum cek role
        if (!$user) {
            return parent::getEloquentQuery();
        }

        $query = parent::getEloquentQuery(); // Dapatkan query dasar dari Resource

        // Jika yang login adalah Guru (bukan Admin)
        if ($user->hasRole('guru')) {
            $teacher = Teacher::where('user_id', auth()->id())->first(); // Dapatkan data guru berdasarkan user yang login
            
            if ($teacher) {
                return $query->whereHas('teaching', function($q) use ($teacher) { // Filter data berdasarkan guru
                    $q->where('teacher_id', $teacher->id); // Hanya ambil data penilaian yang diajar oleh guru ini
                });
            }
        }

        // Jika Admin, kembalikan semua data
        return $query;
    }
}
