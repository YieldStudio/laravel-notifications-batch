<?php

namespace YieldStudio\NotificationsBatch;

use Illuminate\Support\Manager;
use YieldStudio\NotificationsBatch\Contracts\BatchHandlerInterface;
use InvalidArgumentException;

class BatchHandlerManager extends Manager
{
    public function getDefaultDriver(): string
    {
        throw new InvalidArgumentException('No default batch handler driver can be specified.');
    }

    public function channel(?string $channel = null): BatchHandlerInterface
    {
        return $this->driver($channel);
    }

    public function getAvailableChannels(): array
    {
        $channels = [];

        // Add custom creators
        foreach (array_keys($this->customCreators) as $channel) {
            $channels[] = $channel;
        }

        // Add built-in drivers by scanning for create methods
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if ($method !== 'createDriver' && str_starts_with($method, 'create') && str_ends_with($method, 'Driver')) {
                $channel = strtolower(substr($method, 6, -6));
                $channels[] = $channel;
            }
        }

        return array_unique($channels);
    }
}
