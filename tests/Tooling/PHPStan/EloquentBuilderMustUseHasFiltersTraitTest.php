<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\EloquentBuilderMustUseHasFiltersTrait;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class EloquentBuilderMustUseHasFiltersTraitTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new EloquentBuilderMustUseHasFiltersTrait;
    }

    #[Test]
    public function it_passes_when_the_builder_uses_the_has_filters_trait(): void
    {
        $this->analyse([$this->getFixturePath('Builder.php')], []);
    }

    #[Test]
    public function it_fails_when_the_builder_does_not_use_the_has_filters_trait(): void
    {
        $this->analyse([$this->getFixturePath('BuilderWithoutTrait.php')], [
            [
                'Eloquent builders must use the HasFilters trait.',
                16,
            ],
        ]);
    }
}