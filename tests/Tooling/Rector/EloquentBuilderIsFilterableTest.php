<?php

declare(strict_types=1);

namespace Tests\Tooling\Rector;

use Tests\TestCase;
use Tooling\Concerns\ValidatesTraits;
use PHPUnit\Framework\Attributes\Test;
use Tooling\Concerns\ParsesNodes;
use Tests\Tooling\Concerns\GetsFixtures;
use Support\Database\Eloquent\HasFilters;
use Tooling\Concerns\ValidatesInterfaces;
use Tooling\Rector\Rules\EloquentBuilderIsFilterable;
use Support\Database\Eloquent\Contracts\Filterable;

class EloquentBuilderIsFilterableTest extends TestCase
{
    use GetsFixtures;
    use ParsesNodes;
    use ValidatesInterfaces;
    use ValidatesTraits;
    
    #[Test]
    public function it_adds_the_contract_to_the_builder(): void
    {
        $classNode = $this->getClassNode($this->getFixturePath('BuilderWithoutContract.php'));

        $this->assertFalse($this->implementsInterface($classNode, Filterable::class));

        $result = app(EloquentBuilderIsFilterable::class)->refactor($classNode);

        $this->assertTrue($this->implementsInterface($result, Filterable::class));
    }

    #[Test]
    public function it_adds_the_trait_to_the_builder(): void
    {
        $classNode = $this->getClassNode($this->getFixturePath('BuilderWithoutTrait.php'));

        $this->assertFalse($this->usesTrait($classNode, HasFilters::class));

        $result = app(EloquentBuilderIsFilterable::class)->refactor($classNode);

        $this->assertTrue($this->usesTrait($result, HasFilters::class));
    }
}