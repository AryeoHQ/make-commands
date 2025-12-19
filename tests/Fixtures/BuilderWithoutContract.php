<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Support\Database\Eloquent\HasFilters;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends EloquentBuilder<TModel>
 */
final class BuilderWithoutContract extends EloquentBuilder
{
    use HasFilters;

}
