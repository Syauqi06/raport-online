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
            'signature_base64' => 'required', // Validasi string base64
            'parent_note' => 'nullable|string',
        ]);

        // 1. Ambil data Base64
        $image_64 = $request->signature_base64; // data:image/png;base64,iVBORw0KGgo...
        
        // 2. Bersihkan header data (data:image/png;base64,)
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .png
        $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
        $image = str_replace($replace, '', $image_64); 
        $image = str_replace(' ', '+', $image); 

        // 3. Generate Nama File Unik
        $imageName = 'parent_signatures/' . Str::random(10) . '.' . $extension;

        // 4. Simpan File Fisik ke Storage
        Storage::disk('public')->put($imageName, base64_decode($image));

        // 5. Simpan ke Database
        $user = Auth::user();
        $student = \App\Models\Student::where('user_id', $user->id)->firstOrFail();
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->firstOrFail();

        \App\Models\ReportAcknowledgment::create([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear->id,
            'signature_file' => $imageName, // Simpan path-nya
            'parent_note' => $request->parent_note,
        ]);

        return redirect()->back()->with('success', 'Tanda tangan berhasil dikirim!');
    }
}
