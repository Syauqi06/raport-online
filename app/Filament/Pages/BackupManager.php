<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Auth;

class BackupManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationLabel = 'Backup Database';
    protected static ?string $title = 'Backup Manager';
    protected static string $view = 'filament.pages.backup-manager';
    protected static ?string $navigationGroup = 'Pengaturan Sistem';

    // Pastikan hanya Admin yang bisa akses
    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return $user->hasRole('admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return $user->hasRole('admin') && Auth::check();
    }

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        abort_unless($user->hasRole('admin'), 403);
    }

    // Download File
    public function downloadBackup(string $path): BinaryFileResponse
    {
        return response()->download(storage_path('app/' . $path));
    }

    // Hapus File
    public function deleteBackup(string $path): void
    {
        Storage::disk('local')->delete($path);
        
        Notification::make()
            ->title('File backup dihapus')
            ->success()
            ->send();
    }

    // Ambil Data File untuk Tampilan
    protected function getViewData(): array
    {
        $allFiles = Storage::disk('local')->allFiles();

        $backups = collect($allFiles)
            ->filter(fn ($file) => str_ends_with($file, '.zip'))
            ->filter(fn ($file) => str_contains($file, '202')) 
            ->map(function ($file) {
                return [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => $this->formatSize(Storage::disk('local')->size($file)),
                    'date' => date('d M Y, H:i:s', Storage::disk('local')->lastModified($file)),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return [
            'backups' => $backups,
        ];
    }

    // Helper: Format Ukuran File
    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }
}