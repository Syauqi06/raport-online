<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    // Fungsi ini jalan OTOMATIS saat tombol "Create" ditekan
    protected function handleRecordCreation(array $data): Model
    {
        // Ambil data User dari form (name, email, password)
        $userData = [ // Siapkan data user
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // password sudah di-hash di model User
            'is_active' => true,
        ];

        // Hapus field yang tidak ada di tabel students
        unset($data['name']);
        unset($data['email']);
        unset($data['password']);
        unset($data['password_confirmation']); // Untuk konfirmasi password

        $user = User::create($userData); // Buat data user baru
        $user->assignRole('siswa'); // Beri role 'siswa' ke user baru
        $data['user_id'] = $user->id; // Set user_id di data student

        return static::getModel()::create($data); // Buat data student baru (panggil fungsi bawaan static::getModel()::create)
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
