<?php

namespace YieldStudio\NotificationsBatch\Contracts;

interface BatchHandlerInterface
{
    /**
     * Handle batch processing for a specific channel.
     */
    public function handle(): void;
}