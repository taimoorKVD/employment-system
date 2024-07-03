<?php

namespace App\Filament\Resources\StateResource\Pages;

use App\Filament\Exports\StateExporter;
use App\Filament\Resources\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStates extends ListRecords
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(StateExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
