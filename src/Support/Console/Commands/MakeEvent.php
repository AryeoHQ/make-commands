<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Foundation\Console\EventMakeCommand;
use Illuminate\Support\Str;
use Support\Console\Concerns\WithDomainModelContext;

class MakeEvent extends EventMakeCommand
{
    use WithDomainModelContext;

    protected $hidden = true;

    protected function getStub()
    {
        return __DIR__.'/stubs/event.stub';
    }

    protected function rootNamespace()
    {
        $plural = Str::plural($this->domainModel);

        return "App\\Models\\{$plural}\\Events";
    }
}
