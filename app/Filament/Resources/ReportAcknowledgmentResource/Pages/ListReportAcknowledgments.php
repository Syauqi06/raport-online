<?php

namespace App\Filament\Resources\ReportAcknowledgmentResource\Pages;

use App\Filament\Resources\ReportAcknowledgmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportAcknowledgments extends ListRecords
{
    protected static string $resource = ReportAcknowledgmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
