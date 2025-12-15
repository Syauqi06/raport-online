<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportAcknowledgment;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RaportAcknowledgmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'signed_document' => 'required|mimes:pdf|max:5120',  // Dokumen harus berformat PDF dan maksimal 5 MB
            'parent_note' => 'nullable|string',
        ]);

        $user = Auth::user();
        $student = \App\Models\Student::where('user_id', $user->id)->firstOrFail();

        $activeYear = \App\Models\AcademicYear::where('is_active', true)->firstOrFail();

        //  Proses Upload File
        if ($request->hasFile('signed_document')) {
            // format nama file agar tampilannya lebih rapi
            $fileName = 'raport_signed_' . str_replace(' ', '_', $student->user->name) . '_' . time() . '.pdf';
            
            // Simpan di folder 'signed_raports' di disk public
            $path = $request->file('signed_document')->storeAs('signed_raports', $fileName, 'public');
        }

        // Simpan ke Database
        // pakai updateOrCreate agar jika ortu upload ulang, data lama tertimpa (tidak duplikat)
        \App\Models\ReportAcknowledgment::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $activeYear->id,
            ],
            [
                'signature_file' => $path, // Simpan path gambar
                'parent_note' => $request->parent_note,
            ]
        );

        return redirect()->back()->with('success', 'Dokumen raport bertanda tangan berhasil dikirim!');
        }
}
