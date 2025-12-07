<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tahun Ajaran
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal "2023/2024"
            $table->string('semester'); // Misal "Ganjil" atau "Genap"
            $table->boolean('is_active')->default(false); // Menandai tahun ajaran aktif
            $table->timestamps();
        });

        // Kelas
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal "10 IPA 1"
            $table->string('level'); // Misal "10", "11", "12"
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete(); // Menghubungkan ke tahun ajaran
            $table->timestamps();
        });

        // Mata Pelajaran
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal "Matematika"
            $table->string('code')->unique(); // Misal "MAT101"
            $table->integer('kkm')->default(80); // Kriteria Ketuntasan Minimal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_structures');
    }
};
