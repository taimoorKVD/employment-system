<?php

namespace App\Observers;

use App\Models\Task;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;

class TaskObserver
{
    public function created(Task $task): void
    {
        Notification::make()
            ->title('You have a new task:' . $task->name)
            ->sendToDatabase($task->assignedTo);
            //->broadcast($task->assignedTo);
        //event(new DatabaseNotificationsSent($task->assignedTo));
    }
}
