<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Support\Console\Concerns\WithDomainControllerContext;

class MakeRequest extends RequestMakeCommand
{
    use WithDomainControllerContext;

    protected $hidden = true;

    protected function getStub()
    {
        return __DIR__.'/stubs/request.stub';
    }

    protected function getNameInput()
    {
        return 'Request';
    }
}
