<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\ModelMustHaveCollection;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class ModelMustHaveCollectionTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new ModelMustHaveCollection;
    }

    #[Test]
    public function it_passes_when_the_model_has_a_collection(): void
    {
        $this->analyse([$this->getFixturePath('Post.php')], []);
    }

    #[Test]
    public function it_fails_when_the_model_does_not_have_a_collection(): void
    {
        $this->analyse([$this->getFixturePath('PostWithoutCollection.php')], [
            [
                'Eloquent models must have an Illuminate\Database\Eloquent\Attributes\CollectedBy attribute.',
                13,
            ],
        ]);
    }
}
