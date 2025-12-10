<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teaching_id',
        'type',
        'score',
        'description',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    // Relasi ke Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Teaching
    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }
}
