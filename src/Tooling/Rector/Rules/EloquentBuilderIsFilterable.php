<?php

declare(strict_types=1);

namespace Tooling\Rector\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Support\Database\Eloquent\Contracts\Filterable;
use Support\Database\Eloquent\HasFilters;

class EloquentBuilderIsFilterable extends BaseRule
{
    public function handle(Node $node): null|Node
    {
        /** @var Class_ $node */
        $this->ensureTraitIsUsed($node, HasFilters::class);
        $this->ensureInterfaceIsImplemented($node, Filterable::class);

        return $node;
    }

    public function shouldRefactor(Node $node): bool
    {
        if (! $node instanceof Class_) {
            return false;
        }

        if (! $this->extendsClass($node, Builder::class, 'EloquentBuilder')) {
            return false;
        }

        return true;
    }
}
