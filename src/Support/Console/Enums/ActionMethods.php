<?php

declare(strict_types=1);

namespace Support\Console\Enums;

use Support\Routing\Enums\Method;

enum ActionMethods: string
{
    case Get = Method::Get->value;
    case Post = Method::Post->value;
}
