<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\ModelMustHaveEloquentBuilder;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class ModelMustHaveEloquentBuilderTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new ModelMustHaveEloquentBuilder;
    }

    #[Test]
    public function it_passes_when_the_model_has_an_eloquent_builder(): void
    {
        $this->analyse([$this->getFixturePath('Post.php')], []);
    }

    #[Test]
    public function it_fails_when_the_model_does_not_have_an_eloquent_builder(): void
    {
        $this->analyse([$this->getFixturePath('PostWithoutBuilder.php')], [
            [
                'Eloquent models must have an Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder attribute.',
                13,
            ],
        ]);
    }
}
