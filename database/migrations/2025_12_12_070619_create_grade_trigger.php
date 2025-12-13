<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER before_insert_grades
            BEFORE INSERT ON grades
            FOR EACH ROW
            BEGIN
                IF NEW.score >= 90 THEN
                    SET NEW.description = CONCAT("Sangat Baik (A). ", COALESCE(NEW.description, ""));
                ELSEIF NEW.score >= 75 THEN
                    SET NEW.description = CONCAT("Baik (B). ", COALESCE(NEW.description, ""));
                ELSE
                    SET NEW.description = CONCAT("Perlu Perbaikan (C). ", COALESCE(NEW.description, ""));
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS before_insert_grades');
    }
};
