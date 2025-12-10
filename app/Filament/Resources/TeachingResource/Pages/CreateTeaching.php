<?php

namespace App\Filament\Resources\TeachingResource\Pages;

use App\Filament\Resources\TeachingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTeaching extends CreateRecord
{
    protected static string $resource = TeachingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
