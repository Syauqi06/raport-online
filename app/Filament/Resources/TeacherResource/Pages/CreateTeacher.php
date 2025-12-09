<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Persiapkan data untuk User
        $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'is_active' => true,
        ];

        // Hapus data 'name', 'email', dan 'password' dari $data
        unset($data['name']);
        unset($data['email']);
        unset($data['password']);
        unset($data['password_confirmation']);

        $user = User::create($userData); // Buat user baru dengan data yang telah disiapkan
        $user->assignRole('guru'); // Assign role guru ke user yang baru dibuat

        $data['user_id'] = $user->id; // Simpan ID user yang baru dibuat ke data guru

        return static::getModel()::create($data); // Buat guru baru dengan data yang telah disiapkan
    }

    protected function getRedirectUrl(): string
    {
        return TeacherResource::getUrl('index'); // Redirect ke halaman index setelah berhasil membuat guru
    }
}
