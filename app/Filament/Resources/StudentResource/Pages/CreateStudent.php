<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        DB::beginTransaction(); // Mulai transaksi database

        // try-catch untuk menangani error pada proses pembuatan
        try {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'is_active' => true,
            ];

            // Bersihkan data array
            unset($data['name']);
            unset($data['email']);
            unset($data['password']);
            unset($data['password_confirmation']);

            // Buat User dan assign role 'siswa'
            $user = User::create($userData);
            $user->assignRole('siswa');

            // Buat data Student dan hubungkan dengan User
            $data['user_id'] = $user->id;
            $student = static::getModel()::create($data);

            // lakukan commit jika semua proses berhasil
            DB::commit();

            return $student;

        } catch (\Exception $e) { // Tangkap error jika ada
            // Lakukan rollback jika ada error
            DB::rollBack();

            // Lempar notifikasi error ke layar
            Notification::make()
                ->title('Gagal Membuat Siswa')
                ->body($e->getMessage())
                ->danger()
                ->send();

            // Hentikan proses
            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
