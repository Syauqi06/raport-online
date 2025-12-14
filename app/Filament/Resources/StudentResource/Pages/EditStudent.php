<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil record Siswa yang sedang diedit
        $student = $this->getRecord();

        // Siapkan data untuk update ke tabel Users
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        // Cek apakah password diisi? Kalau ada isinya, update password.
        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        // Eksekusi update ke tabel Users
        $student->user->update($userData);

        // Hapus field name, email, dan password
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
