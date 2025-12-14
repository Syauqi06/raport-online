<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'teacher_id', // Menghubungkan ke guru kelas
        'academic_year_id', // Menghubungkan ke tahun ajaran
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class); // Menghubungkan ke tahun ajaran
    }

    public function homeroomTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id'); // Menghubungkan ke guru kelas
    }
}
