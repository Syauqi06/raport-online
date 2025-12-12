<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Grade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RaportController extends Controller
{
    public function print()
    {
        /** 
          * @var \App\Models\User $user 
        */
        $user = Auth::user(); // Mendapatkan pengguna yang sedang masuk
        
        if (!$user->hasRole('siswa')) { // Pastikan hanya siswa yang dapat mengakses fungsi ini
            abort(403, 'Anda bukan siswa!');
        }

        $student = Student::where('user_id', $user->id)->firstOrFail(); // Mendapatkan data siswa berdasarkan user_id

        $grades = Grade::with(['teaching.subject', 'teaching.classroom']) // Memuat relasi yang diperlukan untuk mendapatkan data nilai
            ->where('student_id', $student->id) // Memfilter nilai berdasarkan ID siswa
            ->get();

        $pdf = Pdf::loadView('raport.print', [ // Memuat view untuk PDF
            'student' => $student,
            'grades' => $grades,
            'date' => now()->translatedFormat('d F Y'), // Tanggal hari ini
        ]);

        // Download / Stream PDF
        return $pdf->stream('E-Raport_' . $student->nisn . '.pdf');
    }
}