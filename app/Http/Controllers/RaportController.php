<?php

namespace App\Http\Controllers;

class RaportController extends Controller
{
    public function print($id)
    {
        // Cari siswa berdasarkan ID yang dikirim dari tombol
        $student = \App\Models\Student::with(['classroom.homeroomTeacher', 'user', 'grades'])
            ->findOrFail($id);
            
        // Ambil tahun ajaran aktif
        $academicYear = \App\Models\AcademicYear::where('is_active', true)->first();

        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('raport.print', [
            'student' => $student,
            'academicYear' => $academicYear,
            'date' => now()->format('d F Y'), // Tanggal cetak
        ]);

        return $pdf->stream('Raport-' . $student->user->name . '.pdf');
    }
}