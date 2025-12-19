<?php

declare(strict_types=1);

namespace Tooling\PHPStan\Rules;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;

class ModelMustHaveFactory extends BaseRule
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
            message: 'Eloquent models must have an Illuminate\Database\Eloquent\Attributes\UseFactory attribute.',
            line: $node->getStartLine(),
            identifier: 'model.attributes.factory'
        );
    }

    public function passes(Class_ $node, Scope $scope): bool
    {
        if (! $this->extendsClass($node, Model::class)) {
            return true;
        }

        return $this->classHasAttribute($node, UseFactory::class);
    }
}
