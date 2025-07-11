<?php

namespace YieldStudio\NotificationsBatch;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use YieldStudio\NotificationsBatch\Listeners\InterceptBatchableNotification;
use YieldStudio\NotificationsBatch\Console\Commands\ProcessNotificationsBatch;

class NotificationsBatchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/notifications-batch.php', 'notifications-batch');

        $this->app->singleton(BatchHandlerManager::class, function ($app) {
            return new BatchHandlerManager($app);
        });

        $this->app->alias(BatchHandlerManager::class, 'notifications-batch');

        $this->commands([
            ProcessNotificationsBatch::class,
        ]);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Event::listen(NotificationSending::class, InterceptBatchableNotification::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
                __DIR__ . '/../config/notifications-batch.php' => config_path('notifications-batch.php'),
            ]);
        }
    }
}
