<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Actions\Action;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function afterCreate(): void {
        /** @var Task $task */
        $task = $this->record;

        /** @var User $user */
        $user = auth()->user();

        Notification::make()
            ->title('New task')
            ->icon('heroicon-o-briefcase')
            ->body("**A new task has been assigned to {$task->assignedTo?->name} by {$task->createdBy?->name}.**")
            ->actions([
                Action::make('View')
                    ->url(TaskResource::getUrl('edit', ['record' => $task])),
            ])
            ->sendToDatabase($user);
    }
}
