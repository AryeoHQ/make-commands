<?php

declare(strict_types=1);

namespace Tooling\PHPStan\Rules;

use Illuminate\Support\ServiceProvider;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;

class ServiceProviderIsFinal extends BaseRule
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
            message: 'Service providers must be final.',
            line: $node->getStartLine(),
            identifier: 'serviceProvider.final'
        );
    }

    public function passes(Class_ $node, Scope $scope): bool
    {
        if (! $this->extendsClass($node, ServiceProvider::class)) {
            return true;
        }

        return $node->isFinal();
    }
}
