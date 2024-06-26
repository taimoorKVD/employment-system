<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    public function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Permission Successfully Created')
            ->body('The permission has been successfully created.');
    }
}
