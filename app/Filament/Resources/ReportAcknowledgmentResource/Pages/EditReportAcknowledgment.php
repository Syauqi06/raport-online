<?php

namespace App\Filament\Resources\ReportAcknowledgmentResource\Pages;

use App\Filament\Resources\ReportAcknowledgmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportAcknowledgment extends EditRecord
{
    protected static string $resource = ReportAcknowledgmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
