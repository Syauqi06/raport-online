<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradeResource\Pages;
use App\Filament\Resources\GradeResource\RelationManagers;
use App\Models\Grade;
use App\Models\Student;
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
use Filament\Forms\Get;
use Illuminate\Validation\Rules\Unique;
use App\Models\Teaching;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\ToggleColumn;

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
                ->options(function () {
                            /** @var \App\Models\User $user */
                            $user = auth()->user(); // Dapatkan user yang sedang login

                            $query = Teaching::query()->with(['subject', 'classroom']); // Mulai query ke model Teaching dengan relasi subject dan classroom

                            if ($user->hasRole('guru')) { // Jika yang login adalah guru
                                $teacher = Teacher::where('user_id', auth()->id())->first(); // Dapatkan data guru berdasarkan user yang login
                                if ($teacher) {
                                    $query->where('teacher_id', $teacher->id); // Filter hanya untuk kelas yang diajar oleh guru ini
                                }
                            }

                            return $query->get()->mapWithKeys(function ($item) { // Ubah menjadi array key-value
                                return [$item->id => "{$item->subject->name} - {$item->classroom->name}"]; // Format: "Nama Mapel - Nama Kelas"
                            });
                        })
                    ->searchable()
                    ->live() // Aktifkan live search untuk memperbarui opsi secara dinamis
                    ->afterStateUpdated(fn (callable $set) => $set('student_id', null)) // jika ganti kelas atau mapel maka siswa juga harus diganti
                    ->required(),
                Select::make('student_id')
                    ->label('Siswa')
                    ->options(function (Get $get) {
                        $teachingId = $get('teaching_id'); // Dapatkan teaching_id dari state form

                        if (!$teachingId) { 
                            return []; // Jika belum ada teaching_id, kembalikan array kosong
                        }

                        $teaching = Teaching::find($teachingId); // Dapatkan data Teaching berdasarkan teaching_id

                        if (!$teaching) {
                            return []; // Jika data Teaching tidak ditemukan, kembalikan array kosong
                        }

                        return Student::with('user') // Pastikan relasi user dimuat
                            ->where('classroom_id', $teaching->classroom_id) // Filter siswa berdasarkan classroom_id dari Teaching
                            ->get()
                            ->pluck('user.name', 'id'); // Kembalikan array dengan format [id => nama siswa]
                    })
                    ->searchable()
                    ->required(),
                Select::make('type')
                    ->label('Jenis Nilai')
                    ->options([
                        'TUGAS' => 'Tugas',
                        'UH' => 'Ulangan Harian',
                        'UTS' => 'UTS',
                        'UAS' => 'UAS',
                    ])
                    ->unique(modifyRuleUsing: function (Unique $rule, Get $get) { // Validasi unik berdasarkan kombinasi student_id, teaching_id, dan type
                        return $rule
                            ->where('student_id', $get('student_id')) // Filter berdasarkan student_id
                            ->where('teaching_id', $get('teaching_id')); // Filter berdasarkan teaching_id
                        }, ignoreRecord: true) // Hanya validasi unik saat membuat data
                    // ->validationMessages([ // Customize pesan validasi
                    //     'unique' => 'Jenis nilai ini sudah ada untuk siswa dan mata pelajaran yang dipilih.',
                    // ])
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
                    ->label('Status Kunci')
                    ->onColor('success')  // Hijau = Terkunci (Aman)
                    ->offColor('danger')  // Merah = Draft (Belum tampil)
                    ->onIcon('heroicon-s-lock-closed')
                    ->offIcon('heroicon-s-lock-open'),
                ]);
    }

    public static function table(Table $table): Table
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
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
                BulkAction::make('lock_grades')
                    ->label('Kunci Nilai Terpilih')
                    ->icon('heroicon-o-lock-closed')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        // Update semua yang dipilih jadi is_locked = true
                        $records->each->update(['is_locked' => true]);
                }),
                BulkAction::make('unlock_grades')
                    ->label('Buka Kunci')
                    ->icon('heroicon-o-lock-open')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records) {
                        $records->each->update(['is_locked' => false]);
                    })
                    // Hanya admin yang boleh buka kunci (misalnya)
                    ->visible(fn () => $user->hasRole('admin')),
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
