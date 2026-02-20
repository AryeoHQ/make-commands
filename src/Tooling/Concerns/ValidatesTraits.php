<?php

declare(strict_types=1);

namespace Tooling\Concerns;

use Illuminate\Support\Str;
use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;

trait ValidatesTraits
{
    public function usesTrait(Class_ $node, string $trait): bool
    {
        if ($node->stmts === []) {
            return false;
        }

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\TraitUse) {
                foreach ($stmt->traits as $implementedTrait) {
                    if ($implementedTrait instanceof FullyQualified
                        && $implementedTrait->toString() === $trait
                    ) {
                        return true;
                    }

                    if ($implementedTrait->toString() === Str::afterLast($trait, '\\')) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
