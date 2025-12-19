<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Foundation\Console\TestMakeCommand;
use Support\Console\Concerns\WithDomainControllerContext;

class MakeTestForController extends TestMakeCommand
{
    use WithDomainControllerContext;

    protected $hidden = true;

    protected function getStub()
    {
        return __DIR__.'/stubs/controller-test.stub';
    }

    protected function getNameInput()
    {
        return 'ControllerTest';
    }
}
