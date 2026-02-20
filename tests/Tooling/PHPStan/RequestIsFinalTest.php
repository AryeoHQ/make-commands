<?php

declare(strict_types=1);

namespace Tests\Tooling\PHPStan;

use PHPUnit\Framework\Attributes\Test;
use Tooling\PHPStan\Rules\FormRequestIsFinal;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Rules\Rule;
use Tests\Tooling\Concerns\GetsFixtures;

class RequestIsFinalTest extends RuleTestCase
{
    use GetsFixtures;

    protected function getRule(): Rule
    {
        return new FormRequestIsFinal;
    }

    #[Test]
    public function it_passes_when_the_request_is_final(): void
    {
        $this->analyse([$this->getFixturePath('Request.php')], []);
    }

    #[Test]
    public function it_fails_when_the_request_is_not_final(): void
    {
        $this->analyse([$this->getFixturePath('RequestNotFinal.php')], [
            [
                'Form requests must be final.',
                9,
            ],
        ]);
    }
}