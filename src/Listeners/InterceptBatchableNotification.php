<?php

namespace YieldStudio\NotificationsBatch\Listeners;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;
use YieldStudio\NotificationsBatch\Batchable;
use YieldStudio\NotificationsBatch\Models\PendingNotificationsBatch;

class InterceptBatchableNotification
{
    use SerializesAndRestoresModelIdentifiers;

    public function handle(NotificationSending $event): ?bool
    {
        $notification = $event->notification;
        $notifiable = $event->notifiable;
        $channel = $event->channel;

        if (! $this->shouldBatch($notification, $notifiable, $channel)) {
            return null;
        }

        PendingNotificationsBatch::create([
            'channel' => $channel,
            'payload' => [
                'notificationType' => get_class($notification),
                'notification' => serialize($notification),
                'notifiable' => serialize($this->getSerializedPropertyValue($notifiable)),
            ]
        ]);

        return false;
    }

    private function shouldBatch($notification, $notifiable, string $channel): bool
    {
        if (! $this->usesBatchableTrait($notification)) {
            return false;
        }

        if (method_exists($notification, 'shouldBatch')) {
            return $notification->shouldBatch($notifiable, $channel);
        }

        return true;
    }

    private function usesBatchableTrait($notification): bool
    {
        return in_array(Batchable::class, class_uses_recursive($notification));
    }
}
