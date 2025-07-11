<?php

namespace YieldStudio\NotificationsBatch;

trait Batchable
{
    /**
     * Determine if the notification should be batched for the given notifiable and channel.
     *
     * @param mixed $notifiable
     * @param string $channel
     * @return bool
     */
    public function shouldBatch(mixed $notifiable, string $channel): bool
    {
        return true;
    }
}
