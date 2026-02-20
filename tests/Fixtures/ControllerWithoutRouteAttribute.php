<?php

namespace Tests\Fixtures;

use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

final class ControllerWithoutRouteAttribute
{
    public function __invoke(Request $request)
    {
        //
    }
}
