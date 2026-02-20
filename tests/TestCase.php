<?php

namespace Tests;

use Orchestra\Testbench;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Support\Providers\MakeCommandsServiceProvider;

abstract class TestCase extends Testbench\TestCase
{
    use RefreshDatabase;

    /** @var \Illuminate\Testing\TestResponse|null */
    public static $latestResponse = null;

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app)
    {
        return [
            MakeCommandsServiceProvider::class,
        ];
    }
}
