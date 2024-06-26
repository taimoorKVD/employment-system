<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Role Successfully Created')
            ->body('The role has been successfully created.');
    }
}
