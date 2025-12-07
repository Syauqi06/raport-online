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
            'email' => 'saulgoodman@gmail.com',
            'password' => 'password', // Password akan di-hash otomatis oleh model User
            'is_active' => true,
        ]);

        $admin->assignRole($roleAdmin); // Menetapkan peran admin ke user super admin
    }
}
