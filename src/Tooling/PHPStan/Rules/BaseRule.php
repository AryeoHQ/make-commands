<?php

declare(strict_types=1);

namespace Tooling\PHPStan\Rules;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Tooling\Concerns;

/**
 * @implements Rule<Class_>
 */
abstract class BaseRule implements Rule
{
    use Concerns\ValidatesAttributes;
    use Concerns\ValidatesInheritance;
    use Concerns\ValidatesInterfaces;
    use Concerns\ValidatesTraits;

    abstract public function passes(Class_ $node, Scope $scope): bool;

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    public function buildError(string $message, int $line, string $identifier): array
    {
        return [
            RuleErrorBuilder::message($message)
                ->line($line)
                ->identifier($identifier)
                ->build(),
        ];
    }
}
