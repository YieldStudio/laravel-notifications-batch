<?php

namespace YieldStudio\NotificationsBatch\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesAndRestoresModelIdentifiers;

class PendingNotificationsBatch extends Model
{
    use SerializesAndRestoresModelIdentifiers;

    protected $guarded = ['id'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function rehydrateNotification(): ?Notification
    {
        if (!class_exists($this->payload['notificationType'])) {
            return null;
        }

        $notification = unserialize($this->payload['notification']);

        if (!($notification instanceof Notification)) {
            return null;
        }

        return $notification;
    }

    public function rehydrateNotifiable(): mixed
    {
        return $this->getRestoredPropertyValue(unserialize($this->payload['notifiable']));
    }
}
