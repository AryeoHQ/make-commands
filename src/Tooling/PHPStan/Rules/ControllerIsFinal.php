<?php

declare(strict_types=1);

namespace Tooling\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;

class ControllerIsFinal extends BaseRule
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
            message: 'Controllers must be final.',
            line: $node->getStartLine(),
            identifier: 'controller.final'
        );
    }

    public function passes(Class_ $node, Scope $scope): bool
    {
        if (! str_contains($node->name?->toString(), 'Controller')) {
            return true;
        }

        return $node->isFinal();
    }
}
