<?php

declare(strict_types=1);

namespace Support\Console\Concerns;

use Illuminate\Support\Str;
use Support\Models\Model;

/**
 * @mixin \Illuminate\Console\GeneratorCommand
 */
trait WithDomainModelContext
{
    use SearchesDomainModels;

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->askDomainModelQuestion();

        parent::handle();

        return null;
    }

    protected function rootNamespace()
    {
        $plural = Str::plural($this->domainModel);

        return "App\\Models\\{$plural}";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->rootNamespace();
    }

    protected function getEventClassName(string $event): string
    {
        return ucfirst($event);
    }

    protected function getSementicEventName(): string
    {
        $model = Str::of($this->domainModel)
            ->singular()
            ->lower();
        $event = Str::of($this->getNameInput())
            ->kebab();

        return $model.'.'.$event;
    }

    /**
     * @return array<string>
     */
    protected function observableEvents(): array
    {
        return (new Model)->getObservableEvents();
    }

    protected function getObservableEventsString(): string
    {
        return collect($this->observableEvents())
            ->map(fn ($event) => "        '{$event}' => Events\\{$this->getEventClassName($event)}::class,")
            ->implode("\n");
    }

    protected function buildClass($name)
    {
        return str_replace([
            '{{ domainModelDirectoryNamespace }}',
            '{{ domainModelNamespace }}',
            '{{ domainModelName }}',
            '{{ domainModelVariableName }}',
            '{{ domainModelMorphName }}',
            '{{ domainModelCollectionName }}',
            '{{ domainModelObservableEvents }}',
            '{{ domainModelSemanticEventName }}',
        ],
            [
                $this->rootNamespace(),
                $this->getDomainModelNamespace(),
                Str::singular($this->domainModel),
                Str::of($this->domainModel)->singular()->lower()->toString(),
                Str::of($this->domainModel)->plural()->lower()->toString(),
                Str::plural($this->domainModel),
                $this->getObservableEventsString(),
                $this->getSementicEventName(),
            ],
            parent::buildClass($name)
        );
    }
}
