<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportAcknowledgmentResource\Pages;
use App\Filament\Resources\ReportAcknowledgmentResource\RelationManagers;
use App\Models\ReportAcknowledgment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ReportAcknowledgmentResource extends Resource
{
    protected static ?string $model = ReportAcknowledgment::class;
    protected static ?string $modelLabel = 'Raport Siswa'; // Nama buat tombol dan judul
    protected static ?string $pluralModelLabel = 'Raport Siswa'; // Nama buat menu sidebar
    protected static ?string $navigationLabel = 'Raport Siswa'; // Label di navigasi
    protected static ?string $navigationGroup = 'Data Akademik'; // Grup di navigasi
    protected static ?string $navigationIcon = 'heroicon-o-folder';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Siswa')
                    ->searchable(),
                TextColumn::make('academicYear.name')
                    ->label('Tahun Ajaran'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Waktu Upload'),
                ImageColumn::make('signature_file')
                    ->label('Bukti Tanda Tangan')
                    ->disk('public') // Agar tampilannya bisa diakses melalui URL
                    ->visibility('public') // Agar bisa dilihat publik
                    ->square(), // Agar tampilannya kotak rapi
                TextColumn::make('parent_note')
                    ->label('Catatan Ortu')
                    ->wrap(),
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
            'index' => Pages\ListReportAcknowledgments::route('/'),
            'create' => Pages\CreateReportAcknowledgment::route('/create'),
            'edit' => Pages\EditReportAcknowledgment::route('/{record}/edit'),
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

        $query = parent::getEloquentQuery();

        if ($user->hasRole('guru')) {
            // Ambil data guru
            $teacher = \App\Models\Teacher::where('user_id', auth()->id())->first();
            
            if ($teacher) {
                // Filter hanya siswa yang classroom-nya memiliki teacher_id = guru ini
                return $query->whereHas('student', function ($q) use ($teacher) {
                    $q->whereHas('classroom', function ($c) use ($teacher) {
                        $c->where('teacher_id', $teacher->id);
                    });
                });
            }
        }
        
        return $query;
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
}
