<?php

declare(strict_types=1);

namespace Support\Console\Concerns;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\search;

trait SearchesDomainModels
{
    protected int|string|null $domainModel = null;

    protected function askDomainModelQuestion(): void
    {
        if ($this->option('domain-model') !== null) {
            $this->domainModel = $this->option('domain-model');

            return;
        }

        $this->domainModel ??= search(
            label: 'What is the name of the domain model?',
            options: fn ($search) => $this->getDomainModelOptions($search),
            required: true,
            scroll: 5,
        );
    }

    /**
     * @return array<array-key, string>
     */
    protected function getDomainModelOptions(string $search = ''): array
    {
        return collect(scandir(app_path('Models')))
            ->reject(fn ($dir) => str_starts_with($dir, '.'))
            ->filter(fn ($value) => str_contains($value, strtolower($search)))
            ->values()
            ->toArray();
    }

    protected function getPath($name): string
    {
        return Str::of(base_path().'\\'.$this->rootNamespace().'\\'.$this->getNameInput())
            ->replace('App\\', 'app\\')
            ->replace('\\', '/')
            ->append('.php')
            ->toString();
    }

    /**
     * Get the console command options.
     *
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            ['domain-model', null, InputOption::VALUE_REQUIRED, 'The domain model for the class.'],
        ]);
    }

    protected function getDomainModelNamespace(): string
    {
        return 'App\\Models\\'.Str::plural($this->domainModel).'\\'.Str::singular($this->domainModel);
    }
}
