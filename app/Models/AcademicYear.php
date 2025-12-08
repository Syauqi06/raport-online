<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use HasFactory;

    // Fillable attributes yang dapat diisi
    protected $fillable = [
        'name',
        'semester',
        'is_active',
    ];

    // Konversi nilai is_active ke boolean
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Classroom
    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }
}
