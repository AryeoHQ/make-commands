<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\EloquentBuilderMustBeFinal;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class EloquentBuilderMustBeFinalTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new EloquentBuilderMustBeFinal;
    }

    #[Test]
    public function it_passes_when_the_builder_is_final(): void
    {
        $this->analyse([$this->getFixturePath('Builder.php')], []);
    }

    #[Test]
    public function it_fails_when_the_builder_is_not_final(): void
    {
        $this->analyse([$this->getFixturePath('BuilderNotFinal.php')], [
            [
                'Eloquent builders must be final.',
                16,
            ],
        ]);
    }
}