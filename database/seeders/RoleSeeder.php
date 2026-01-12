<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data dummy untuk roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleGuru = Role::create(['name' => 'guru']);
        $roleSiswa = Role::create(['name' => 'siswa']);

        // Super Admin User
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password', // Password akan di-hash otomatis oleh model User
            'is_active' => true,
        ]);

        $admin->assignRole($roleAdmin); // Menetapkan peran admin ke user super admin

        // Guru User
        $guru = User::create([
            'name' => 'Guru',
            'email' => 'guru@gmail.com',
            'password' => 'password', // Password akan di-hash otomatis oleh model User
            'is_active' => true,
        ]);

        $guru->assignRole($roleGuru); // Menetapkan peran guru ke user guru

        // // Siswa User
        // $siswa = User::create([
        //     'name' => 'Murid',
        //     'email' => 'murid@gmail.com',
        //     'password' => 'password', // Password akan di-hash otomatis oleh model User
        //     'is_active' => true,
        // ]);

        // $siswa->assignRole($roleSiswa); // Menetapkan peran siswa ke user siswa
    }
}
