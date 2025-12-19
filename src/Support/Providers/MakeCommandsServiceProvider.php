<?php

declare(strict_types=1);

namespace Support\Providers;

use Illuminate\Support\ServiceProvider;
use Support\Console\Commands\MakeController;
use Support\Console\Commands\MakeModel;

class MakeCommandsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModel::class,
                MakeController::class,
            ]);
        }
    }
}
