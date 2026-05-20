<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public function createForUser(?int $userId, string $title, ?string $message, string $type = 'system', ?string $link = null, ?int $taskId = null): void
    {
        if (! $userId) {
            return;
        }

        Notification::create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link ?: null,
        ]);
    }
}
