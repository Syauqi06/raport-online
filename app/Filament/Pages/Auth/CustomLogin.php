<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login;
use Illuminate\Contracts\Support\Htmlable;

class CustomLogin extends Login
{
    // Mengganti Judul Besar (Heading)
    public function getHeading(): string|Htmlable
    {
        return 'Selamat Datang';
    }

    // Mengganti Sub-Judul (Di bawah Heading)
    public function getSubheading(): string|Htmlable|null
    {
        return 'Sistem Informasi E-Raport SD Negeri Heavenhold 1';
    }
}