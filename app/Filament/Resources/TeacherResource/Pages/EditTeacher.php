<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil data record Guru yang sedang diedit
        $teacher = $this->getRecord();

        // Siapkan data untuk update tabel Users
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Cek apakah password diisi? Kalau ya, update password baru
        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        // Update tabel Users
        $teacher->user->update($userData);

        // Bersihkan data array yang tidak diperlukan untuk tabel Teachers
        unset($data['name']);
        unset($data['email']);
        unset($data['password']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
