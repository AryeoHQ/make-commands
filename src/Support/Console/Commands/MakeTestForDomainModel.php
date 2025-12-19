<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Foundation\Console\TestMakeCommand;
use Support\Console\Concerns\WithDomainModelContext;

class MakeTestForDomainModel extends TestMakeCommand
{
    use WithDomainModelContext;

    protected $hidden = true;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:domain-model-test';

    protected function getStub()
    {
        return __DIR__.'/stubs/domain-model-test.stub';
    }
}
