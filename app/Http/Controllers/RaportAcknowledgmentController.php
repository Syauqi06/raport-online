<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportAcknowledgment;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;

class RaportAcknowledgmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'signature_file' => 'required|image|max:2048', // Maks 2MB
            'parent_note' => 'nullable|string',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        // Ambil tahun ajaran aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Simpan File
        $path = $request->file('signature_file')->store('parent_signatures', 'public');

        // Simpan ke Database
        ReportAcknowledgment::create([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear->id,
            'signature_file' => $path,
            'parent_note' => $request->parent_note,
        ]);

        return redirect()->back()->with('success', 'Raport yang ditandatangani berhasil dikirim ke Wali Kelas!'); // Kembali ke halaman sebelumnya
    }
}
