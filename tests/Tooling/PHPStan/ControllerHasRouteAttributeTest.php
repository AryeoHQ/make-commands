<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\ControllerHasRouteAttribute;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class ControllerHasRouteAttributeTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new ControllerHasRouteAttribute;
    }

    #[Test]
    public function it_passes_when_the_controller_has_a_route_attribute(): void
    {
        $this->analyse([$this->getFixturePath('Controller.php')], []);
    }

    #[Test]
    public function it_fails_when_the_controller_does_not_have_a_route_attribute(): void
    {
        $this->analyse([$this->getFixturePath('ControllerWithoutRouteAttribute.php')], [
            [
                'Controllers define their endpoints with the Route attribute.',
                8,
            ],
        ]);
    }
}