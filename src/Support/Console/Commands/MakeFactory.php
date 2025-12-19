<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Support\Console\Concerns\WithDomainModelContext;

class MakeFactory extends FactoryMakeCommand
{
    use WithDomainModelContext;

    protected $hidden = true;

    protected function getStub()
    {
        return __DIR__.'/stubs/factory.stub';
    }
}
