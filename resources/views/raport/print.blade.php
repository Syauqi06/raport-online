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
        .info-table td { padding: 5px; vertical-align: top; }
        
        .grades-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grades-table th, .grades-table td { border: 1px solid black; padding: 8px; text-align: center; }
        .grades-table th { background-color: #f2f2f2; }
        .text-left { text-align: left !important; }
        
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SD Negeri 1 Heavenhold</h1>
        <p>Jl. Heavenhold, Kec. Heaven, Kota Heavenhold</p>
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
            <td>: {{ $student->nisn ?? '-' }}</td>
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
                <th width="10%">KKM</th>
                <th width="10%">Nilai</th>
                <th width="10%">Predikat</th>
                <th width="15%">Ket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($student->grades as $grade)
            <tr>
                <td>{{ $loop->iteration }}</td>
                
                {{-- Nama Mapel --}}
                <td class="text-left">{{ $grade->teaching?->subject?->name ?? 'Mapel Dihapus' }}</td>
                
                {{-- KKM --}}
                <td>{{ $grade->teaching?->subject?->kkm ?? 80 }}</td>

                {{-- Logika Kunci Nilai --}}
                @if($grade->is_locked)
                    {{-- Jika Nilai Sudah Dikunci Maka Tampilkan Nilai dan Predikat --}}
                    <td style="font-weight: bold;">{{ $grade->score }}</td>
                    
                    <td>
                        {{-- Predikat --}}
                        @if($grade->score >= 90) A
                        @elseif($grade->score >= 80) B
                        @elseif($grade->score >= 75) C
                        @else D
                        @endif
                    </td>
                    
                    <td>
                        {{-- Keterangan --}}
                        @php $kkm = $grade->teaching?->subject?->kkm ?? 80; @endphp
                        {{ $grade->score >= $kkm ? 'Lulus' : 'Remedial' }}
                    </td>
                @else
                    {{-- Jika Nilai Belum Dikunci Maka Tampilan Kosong dan Tampilkan Keterangan --}}
                    <td style="color: #ff0000; font-style: italic;"> - </td>
                    <td style="color: #ff0000; text-align: center;"> - </td>
                    <td style="color: red; font-size: 10px; font-style: italic;">
                        (Draft Nilai)
                    </td>
                @endif
            </tr>
            @endforeach
    </tbody>
    </table>

    <div class="footer">
        <br><br>
        <table style="width: 100%; border: none;">
            <tr>
                {{-- KOLOM KIRI: ORANG TUA / WALI --}}
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>
                        Mengetahui,<br>
                        Orang Tua / Wali
                    </p>
                    
                    <div style="height: 80px;"></div>

                    <p style="text-decoration: underline; margin-top: 10px;">
                        ( ........................................ )
                    </p>
                </td>

                {{-- KOLOM KANAN: WALI KELAS --}}
                <td style="width: 50%; text-align: center; vertical-align: top;">
                    <p>
                        Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                        Wali Kelas
                    </p>

                    <div style="height: 80px; margin: 10px auto;">
                        @if($student->classroom && $student->classroom->homeroomTeacher && $student->classroom->homeroomTeacher->signature)
                            <img src="file://{{ public_path('storage/' . $student->classroom->homeroomTeacher->signature) }}" 
                                 style="height: 80px; width: auto;" 
                                 alt="Tanda Tangan">
                        @else
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