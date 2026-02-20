<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\EloquentBuilderMustHaveFilterableContract;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class EloquentBuilderMustHaveFilterableContractTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new EloquentBuilderMustHaveFilterableContract;
    }

    #[Test]
    public function it_passes_when_the_builder_has_the_filterable_contract(): void
    {
        $this->analyse([$this->getFixturePath('Builder.php')], []);
    }

    #[Test]
    public function it_fails_when_the_builder_does_not_have_the_filterable_contract(): void
    {
        $this->analyse([$this->getFixturePath('BuilderWithoutContract.php')], [
            [
                'Eloquent builders must implement the Filterable interface.',
                15,
            ],
        ]);
    }
}