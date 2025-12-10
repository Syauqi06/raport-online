<?php

namespace App\Filament\Resources\TeachingResource\Pages;

use App\Filament\Resources\TeachingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeaching extends EditRecord
{
    protected static string $resource = TeachingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
