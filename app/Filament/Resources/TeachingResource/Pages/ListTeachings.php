<?php

namespace App\Filament\Resources\TeachingResource\Pages;

use App\Filament\Resources\TeachingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachings extends ListRecords
{
    protected static string $resource = TeachingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
