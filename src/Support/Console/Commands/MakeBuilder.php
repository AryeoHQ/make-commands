<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Support\Console\Concerns\WithDomainModelContext;

class MakeBuilder extends GeneratorCommand
{
    use WithDomainModelContext;

    protected $hidden = true;

    protected $type = 'Query Builder';

    protected function getStub()
    {
        return __DIR__.'/stubs/builder.stub';
    }
}
