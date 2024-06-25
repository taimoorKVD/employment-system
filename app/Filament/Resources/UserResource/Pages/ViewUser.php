<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Information')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Username'),
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('email_verified_at')
                            ->label('Verified At'),
                        TextEntry::make('team_id')
                            ->label('Project')
                            ->default(Filament::getTenant()->name),
                    ])->columns(3)
            ]);
    }
}
