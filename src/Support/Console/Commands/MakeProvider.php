<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Support\Console\Concerns\WithDomainModelContext;

class MakeProvider extends ProviderMakeCommand
{
    use WithDomainModelContext;

    protected $hidden = true;

    protected function getStub()
    {
        return __DIR__.'/stubs/provider.stub';
    }
}
