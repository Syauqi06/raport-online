<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teaching extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'classroom_id',
    ];

    // Relasi ke Teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // Relasi ke Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Relasi ke Classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
