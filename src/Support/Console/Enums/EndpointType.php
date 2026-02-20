<?php

declare(strict_types=1);

namespace Support\Console\Enums;

enum EndpointType: string
{
    case Rest = 'REST';
    case Action = 'Action';
}
