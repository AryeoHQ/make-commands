<?php

declare(strict_types=1);

namespace Support\Providers;

use Illuminate\Support\ServiceProvider;
use Support\Console\Commands\MakeController;

class MakeCommandsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeController::class,
            ]);
        }
    }
}
