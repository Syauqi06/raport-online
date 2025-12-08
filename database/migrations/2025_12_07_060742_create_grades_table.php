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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete(); // Menghubungkan ke tabel students
            $table->foreignId('teaching_id')->constrained()->cascadeOnDelete(); // Menghubungkan ke tabel teachings
            $table->enum('type', ['TUGAS', 'UH', 'UTS', 'UAS']); // Jenis penilaian
            $table->double('score'); // Nilai
            $table->text('description')->nullable();
            $table->boolean('is_locked')->default(false); // Menandai apakah nilai sudah dikunci
            $table->timestamps();
        });

        // Log aktivitas penilaian
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User yang melakukan perubahan
            $table->string('action'); // Deskripsi aksi CRUD
            $table->string('description'); // Rincian perubahan
            $table->ipAddress('ip_address')->nullable(); // Alamat IP user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades', 'activity_logs');
    }
};
