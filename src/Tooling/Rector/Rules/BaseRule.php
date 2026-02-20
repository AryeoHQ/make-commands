<?php

declare(strict_types=1);

namespace Tooling\Rector\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Rector\AbstractRector;
use Tooling\Concerns;

abstract class BaseRule extends AbstractRector
{
    use Concerns\EnsuresContractUsage;
    use Concerns\EnsuresTraitUsage;
    use Concerns\ParsesNodes;
    use Concerns\ValidatesAttributes;
    use Concerns\ValidatesInheritance;
    use Concerns\ValidatesInterfaces;
    use Concerns\ValidatesTraits;

    public function __construct(
        protected UseNodesToAddCollector $useNodesToAddCollector
    ) {}

    abstract public function shouldRefactor(Node $node): bool;

    abstract public function handle(Node $node): null|Node;

    public function refactor(Node $node): null|Node
    {
        if (! $this->shouldRefactor($node)) {
            return $node;
        }

        return $this->handle($node);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }
}
