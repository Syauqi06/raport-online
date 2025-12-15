<!DOCTYPE html>
<html>
<head>
    <title>E-Raport Siswa</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid black; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        
        .grades-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grades-table th, .grades-table td { border: 1px solid black; padding: 8px; text-align: center; }
        .grades-table th { background-color: #f2f2f2; }
        .text-left { text-align: left !important; }
        
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMA Negeri 69 Ngawi Selatan</h1>
        <p>Jl. Ngawi Selatan, Kec. Ngawi, Kabupaten Nguawi</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Nama</td>
            <td width="35%">: {{ $student->user->name }}</td>
            <td width="15%">Kelas</td>
            <td width="35%">: {{ $student->classroom->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>: {{ $student->nisn }}</td>
            <td>Tahun Ajaran</td>
            <td>: {{ $student->classroom->academicYear->name ?? '-' }}</td>
        </tr>
    </table>

    <h3>Laporan Hasil Belajar</h3>

    <table class="grades-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="40%">Mata Pelajaran</th>
                <th width="15%">KKM</th>
                <th width="15%">Nilai</th>
                <th width="15%">Predikat</th>
                <th width="10%">Ket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $index => $grade)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $grade->teaching->subject->name }}</td>
                <td>{{ $grade->teaching->subject->kkm }}</td>
                <td style="font-weight: bold;">{{ $grade->score }}</td>
                <td>
                    {{-- Logika Predikat Sederhana --}}
                    @if($grade->score >= 90) A
                    @elseif($grade->score >= 80) B
                    @elseif($grade->score >= 75) C
                    @else D
                    @endif
                </td>
                <td>
                    {{ $grade->score >= $grade->teaching->subject->kkm ? 'Lulus' : 'Remedial' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <br><br>
        {{-- Layout Tanda Tangan Menggunakan Tabel (Agar Rapi Kiri-Kanan) --}}
        <table style="width: 100%; border: none;">
            <tr>
                {{-- KOLOM KIRI: ORANG TUA / WALI --}}
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>
                        Mengetahui,<br>
                        Orang Tua / Wali
                    </p>
                    
                    {{-- Space kosong untuk tanda tangan manual orang tua --}}
                    <div style="height: 80px;"></div>

                    <p style="text-decoration: underline; margin-top: 10px;">
                        ( ........................................ )
                    </p>
                </td>

                {{-- KOLOM KANAN: WALI KELAS --}}
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>
                        Jakarta, {{ \Carbon\Carbon::now()->format('d F Y') }}<br>
                        Wali Kelas
                    </p>

                    <div style="height: 80px; margin: 10px auto;">
                        {{-- Cek Data Wali Kelas --}}
                        @if($student->classroom && $student->classroom->homeroomTeacher && $student->classroom->homeroomTeacher->signature)
                            {{-- Tampilkan Gambar Tanda Tangan (Pakai file:// agar terbaca sistem) --}}
                            <img src="file://{{ public_path('storage/' . $student->classroom->homeroomTeacher->signature) }}" 
                                style="height: 80px; width: auto;" 
                                alt="Tanda Tangan">
                        @else
                            {{-- Jika tidak ada tanda tangan, biarkan kosong --}}
                            <br><br><br>
                        @endif
                    </div>

                    <p style="font-weight: bold; text-decoration: underline;">
                        {{ $student->classroom->homeroomTeacher->user->name ?? '( Belum Ada Wali Kelas )' }}
                    </p>
                    <p>
                        NIP. {{ $student->classroom->homeroomTeacher->nip ?? '-' }}
                    </p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>