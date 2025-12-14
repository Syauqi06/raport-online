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

                        <a href="{{ route('raport.print') }}" target="_blank" 
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Upload Foto/Scan Raport (Bagian Tanda Tangan)</label>
                                    <input type="file" name="signature_file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                                </div>
                                
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Catatan untuk Wali Kelas (Opsional)</label>
                                    <textarea name="parent_note" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                                </div>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    Kirim ke Wali Kelas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
