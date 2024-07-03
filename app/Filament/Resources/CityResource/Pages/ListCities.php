<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Exports\CityExporter;
use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    protected static string $resource = CityResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\ExportAction::make()
//                ->exporter(CityExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
