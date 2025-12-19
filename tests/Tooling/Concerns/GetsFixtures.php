<?php

declare(strict_types=1);

namespace Tests\Tooling\Concerns;

trait GetsFixtures
{
    public function getFixturePath(string $filename): string
    {
        return __DIR__.'/../../Fixtures/'.$filename;
    }
}