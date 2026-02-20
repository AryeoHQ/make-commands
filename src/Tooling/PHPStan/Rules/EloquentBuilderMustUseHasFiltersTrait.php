<?php

declare(strict_types=1);

namespace Tooling\PHPStan\Rules;

use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use Support\Database\Eloquent\HasFilters;

class EloquentBuilderMustUseHasFiltersTrait extends BaseRule
{
    /**
     * @param  Class_  $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($this->passes($node, $scope)) {
            return [];
        }

        return $this->buildError(
            message: 'Eloquent builders must use the HasFilters trait.',
            line: $node->getStartLine(),
            identifier: 'builder.uses.hasFilters'
        );
    }

    public function passes(Class_ $node, Scope $scope): bool
    {
        if (! $this->extendsClass($node, Builder::class)) {
            return true;
        }

        return $this->usesTrait($node, HasFilters::class);
    }
}
