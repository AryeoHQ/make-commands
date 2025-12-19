<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends EloquentBuilder<TModel>
 */
class BuilderNotFinal extends EloquentBuilder implements Filterable
{
    use HasFilters;

}
