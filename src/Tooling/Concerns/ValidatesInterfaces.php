<?php

declare(strict_types=1);

namespace Tooling\Concerns;

use Illuminate\Support\Str;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;

trait ValidatesInterfaces
{
    public function implementsInterface(Class_ $node, string $interface): bool
    {
        if ($node->implements === []) {
            return false;
        }

        foreach ($node->implements as $implementedInterface) {
            if ($implementedInterface instanceof FullyQualified && $implementedInterface->toString() === $interface) {
                return true;
            }

            if ($implementedInterface->toString() === Str::afterLast($interface, '\\')) {
                return true;
            }
        }

        return false;
    }
}
