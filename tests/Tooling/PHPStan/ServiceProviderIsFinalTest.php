<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\ServiceProviderIsFinal;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class ServiceProviderIsFinalTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new ServiceProviderIsFinal;
    }

    #[Test]
    public function it_passes_when_the_service_provider_is_final(): void
    {
        $this->analyse([$this->getFixturePath('ServiceProvider.php')], []);
    }

    #[Test]
    public function it_fails_when_the_service_provider_is_not_final(): void
    {
        $this->analyse([$this->getFixturePath('ServiceProviderNotFinal.php')], [
            [
                'Service providers must be final.',
                11,
            ],
        ]);
    }
}