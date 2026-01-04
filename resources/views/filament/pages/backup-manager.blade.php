<x-filament-panels::page>
    
    {{-- INFO BOX: Petunjuk Backup Manual --}}
    <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
        <span class="font-medium">Info Backup:</span> 
        Karena pembatasan keamanan server (Windows Environment), proses pembuatan backup dilakukan melalui terminal.
        <br><br>
        Silakan buka terminal di folder project dan jalankan perintah:
        <br>
        <code class="bg-gray-200 dark:bg-gray-700 px-2 py-1 rounded font-bold">php artisan backup:run</code>
        <br><br>
        Setelah proses selesai, refresh halaman ini untuk mengunduh file backup.
    </div>

    {{-- Tabel Daftar File --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Nama File</th>
                    <th class="px-6 py-3">Ukuran</th>
                    <th class="px-6 py-3">Tanggal Dibuat</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $backup['name'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $backup['size'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $backup['date'] }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            {{-- Tombol Download --}}
                            <button wire:click="downloadBackup('{{ $backup['path'] }}')" 
                                    class="text-blue-600 hover:text-blue-900 font-bold hover:underline">
                                Download
                            </button>
                            
                            {{-- Tombol Hapus --}}
                            <button wire:click="deleteBackup('{{ $backup['path'] }}')" 
                                    wire:confirm="Yakin ingin menghapus file ini?"
                                    class="text-red-600 hover:text-red-900 ml-4 hover:underline">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center">
                            Belum ada file backup. Silakan jalankan perintah di terminal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>