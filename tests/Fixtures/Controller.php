<?php

namespace Tests\Fixtures;

use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

final class Controller
{
    #[Route(
        name: 'controller.index',
        uri: '/controller',
        methods: Method::Get,
    )]
    public function __invoke(Request $request)
    {
        //
    }
}
