<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Exports\CountryExporter;
use App\Filament\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(CountryExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
