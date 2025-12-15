<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    @if(Auth::user()->hasRole('siswa'))
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                            <p class="font-bold">E-Raport Tersedia</p>
                            <p>Silakan unduh hasil belajar Anda melalui tombol di bawah ini.</p>
                        </div>

                        @php
                            // cari data siswa berdasarkan user yang login saat ini
                            $studentId = \App\Models\Student::where('user_id', Auth::id())->value('id');
                        @endphp
                        <a href="{{ route('raport.print', $studentId) }}" target="_blank" 
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cetak E-Raport Saya (PDF)
                        </a>
                    @else
                        <p>Anda login sebagai <strong>{{ Auth::user()->roles->first()->name }}</strong>.</p>
                        <p>Silakan akses <a href="/admin" class="text-blue-600 hover:underline">Panel Admin</a> untuk mengelola data.</p>
                    @endif
                </div>

                <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Pengembalian Raport (Tanda Tangan Orang Tua)</h3>
                        
                        {{-- Cek apakah sudah pernah upload --}}
                        @php
                            $student = \App\Models\Student::where('user_id', Auth::id())->first();
                            $ack = null;
                            if($student) {
                                // Cari data pengembalian di tahun ajaran aktif
                                $ack = \App\Models\ReportAcknowledgment::where('student_id', $student->id)->latest()->first(); 
                            }
                        @endphp

                        @if($ack)
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                                <p>Terima kasih. Anda sudah mengirimkan raport yang ditandatangani pada tanggal {{ $ack->created_at->format('d F Y') }}.</p>
                                <p class="text-sm mt-2">Catatan Anda: "{{ $ack->parent_note }}"</p>
                            </div>
                        @else
                        <form action="{{ route('raport.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                                    <div class="flex">
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                <strong>Instruksi:</strong><br>
                                                1. Silakan download Raport Siswa (PDF) melalui tombol di atas.<br>
                                                2. Print dan tanda tangani, ATAU tanda tangani langsung file PDF tersebut secara digital.<br>
                                                3. Upload kembali file PDF yang sudah bertanda tangan di bawah ini.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Upload Dokumen Raport (PDF)</label>
                                    {{-- accept=".pdf" membatasi agar user lebih mudah memilih file pdf --}}
                                    <input type="file" name="signed_document" accept=".pdf" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                    <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file: 5MB.</p>
                                </div>
                                
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Catatan (Opsional)</label>
                                    <textarea name="parent_note" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                                </div>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Kirim Dokumen ke Wali Kelas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script>
        var canvas = document.getElementById('signature-pad');

        function resizeCanvas() {
            var ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }
        window.onresize = resizeCanvas;
        resizeCanvas();

        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255,255,255)'
        });


        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
        });

        document.getElementById('signatureForm').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Silakan tanda tangan terlebih dahulu!");
            } else {
                // Masukkan data gambar ke input hidden
                var data = signaturePad.toDataURL('image/png');
                document.getElementById('signature_base64').value = data;
            }
        });
</script> --}}
</x-app-layout>
