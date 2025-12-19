<?php

declare(strict_types=1);

namespace Tooling\Concerns;

use PhpParser\Node\Stmt\Class_;

trait ValidatesInheritance
{
    public function extendsClass(Class_ $node, string $class, null|string $alias = null): bool
    {
        if ($node->extends === null) {
            return false;
        }

        return $node->extends->toString() === $class || $node->extends->toString() === $alias;
    }
}
