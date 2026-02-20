<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Tests\Fixtures\Post;

class ServiceProviderNotFinal extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'post' => Post::class,
        ]);
    }
}
