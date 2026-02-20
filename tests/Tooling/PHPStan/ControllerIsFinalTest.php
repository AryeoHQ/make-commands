<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\ControllerIsFinal;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class ControllerIsFinalTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new ControllerIsFinal;
    }

    #[Test]
    public function it_passes_when_the_controller_is_final(): void
    {
        $this->analyse([$this->getFixturePath('Controller.php')], []);
    }

    #[Test]
    public function it_fails_when_the_controller_is_not_final(): void
    {
        $this->analyse([$this->getFixturePath('ControllerNotFinal.php')], [
            [
                'Controllers must be final.',
                8,
            ],
        ]);
    }
}