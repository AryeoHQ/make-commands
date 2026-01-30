<?php

declare(strict_types=1);

namespace Support\Console\Concerns;

use Illuminate\Support\Str;
use Support\Console\Enums\ActionMethods;
use Support\Console\Enums\Endpoints;
use Support\Console\Enums\EndpointType;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

/**
 * @mixin \Illuminate\Console\GeneratorCommand
 */
trait WithDomainControllerContext
{
    use SearchesDomainModels;

    protected string|null $apiVersion = null;

    /**
     * @var array<array-key, string>|null
     */
    protected array|null $endpoints = null;

    protected string|null $endpoint = null;

    protected string|null $endpointType = null;

    protected string|null $actionName = null;

    public const NEW_API_VERSION_OPTION = 'Create new API version';

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->askApiVersionQuestion();
        $this->askDomainModelQuestion();
        $this->askEndpointTypeQuestion();
        $this->askEndpointsQuestion();

        parent::handle();

        return null;
    }

    protected function askApiVersionQuestion(): void
    {
        if ($this->option('api-version') !== null) {
            $this->apiVersion = $this->option('api-version');

            return;
        }

        $apiVersionOptions = $this->getApiVersionOptions();

        $this->apiVersion ??= select(
            label: 'What is the API version?',
            options: [...$apiVersionOptions, self::NEW_API_VERSION_OPTION],
            required: true,
            scroll: 5,
            default: end($apiVersionOptions),
        );

        if ($this->apiVersion === self::NEW_API_VERSION_OPTION) {
            $this->apiVersion = $this->getNextApiVersion();
        }
    }

    /**
     * @return array<array-key, string>
     */
    protected function getApiVersionOptions(): array
    {
        if (! is_dir(app_path('Http/Api'))) {
            return [];
        }

        return collect(scandir(app_path('Http/Api')))
            ->reject(fn ($dir) => str_starts_with($dir, '.'))
            ->filter(fn ($value) => str_starts_with($value, 'V'))
            ->values()
            ->toArray();
    }

    protected function askEndpointTypeQuestion(): void
    {
        if ($this->option('type') !== null) {
            $this->endpointType = $this->option('type');

            return;
        }

        $this->endpointType = select(
            label: 'What type of endpoint would you like to create?',
            options: array_column(EndpointType::cases(), 'value'),
            required: true,
        );
    }

    protected function askEndpointsQuestion(): void
    {
        if ($this->endpointType === EndpointType::Action->value) {
            $this->askActionQuestions();

            return;
        }

        if ($this->option('endpoint') !== null) {
            $this->endpoint = $this->option('endpoint');

            return;
        }

        $this->endpoints = multiselect(
            label: 'What endpoints would you like to create?',
            options: array_column(Endpoints::cases(), 'value'),
            scroll: count(Endpoints::cases()),
            required: true,
        );
    }

    protected function askActionQuestions(): void
    {
        $this->actionName = $this->option('action') ?? text(
            label: 'What is the name of the action? (ie: PayInvoice, Download, etc.)',
            required: true,
        );

        if ($this->option('endpoint') !== null) {
            $this->endpoint = $this->option('endpoint');

            return;
        }

        $this->endpoints = [ActionMethods::Post->value];
    }

    protected function setEndpoint(string $endpoint): void
    {
        $this->endpoint = ucfirst($endpoint);
    }

    protected function rootNamespace()
    {
        $pluralDomainModel = Str::plural($this->domainModel);

        $directory = match (true) {
            $this->endpointType === EndpointType::Action->value => 'Actions\\'.Str::of($this->actionName)->studly()->toString(),
            default => $this->endpoint,
        };

        return "App\\Http\\Api\\{$this->apiVersion}\\{$pluralDomainModel}\\{$directory}";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $this->rootNamespace();
    }

    protected function buildClass($name)
    {
        return str_replace([
            '{{ domainControllerDirectoryNamespace }}',
            '{{ domainModelNamespace }}',
            '{{ domainModelBinding }}',
            '{{ domainControllerName }}',
            '{{ domainControllerUri }}',
            '{{ domainControllerMethod }}',
        ],
            [
                $this->rootNamespace(),
                $this->getDomainModelNamespace(),
                Str::singular($this->domainModel).' $'.Str::of($this->domainModel)->singular()->lower()->toString(),
                $this->getDomainControllerName(),
                $this->getDomainControllerUri(),
                $this->getDomainControllerMethod(),
            ],
            parent::buildClass($name)
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array<string, mixed>
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            ['api-version', null, InputOption::VALUE_REQUIRED, 'The API version for the class.'],
            ['domain-model', null, InputOption::VALUE_REQUIRED, 'The domain model for the class.'],
            ['endpoint', null, InputOption::VALUE_REQUIRED, 'The endpoint for the class.'],
            ['type', null, InputOption::VALUE_REQUIRED, 'The type of endpoint (REST or Action) for the class.'],
            ['action', null, InputOption::VALUE_OPTIONAL, 'The action name for the class.'],
        ]);
    }

    private function getNextApiVersion(): string
    {
        if (! is_dir(app_path('Http/Api'))) {
            return 'V1';
        }

        $latestApiVersion = collect(scandir(app_path('Http/Api')))
            ->map(fn ($dir) => Str::of($dir)->replace('V', '')->toString())
            ->filter(fn ($version) => is_numeric($version))
            ->max();

        return 'V'.((int) $latestApiVersion + 1);
    }

    protected function getDomainControllerName(): string
    {
        $name = match (true) {
            $this->endpointType === EndpointType::Action->value => 'actions.'.Str::kebab($this->actionName),
            default => strtolower($this->endpoint),
        };

        return 'api.'.$this->apiVersion.'.'.Str::of($this->domainModel)->plural()->lower().'.'.$name;
    }

    protected function getDomainControllerUri(): string
    {
        $resource = match (true) {
            $this->endpointType === EndpointType::Action->value => '/{'.Str::of($this->domainModel)->singular()->lower().'}/actions/'.Str::kebab($this->actionName),
            Endpoints::from(strtolower($this->endpoint))->isSingleResource() => '/{'.Str::of($this->domainModel)->singular()->lower().'}',
            default => null,
        };

        return 'api/'.strtolower($this->apiVersion).'/'.Str::of($this->domainModel)->plural()->lower().$resource;
    }

    protected function getDomainControllerMethod(): string
    {
        return match (true) {
            $this->endpointType === EndpointType::Action->value => 'Method::'.ActionMethods::from(strtoupper($this->endpoint))->name,
            default => 'Method::'.Endpoints::from(strtolower($this->endpoint))->httpMethod()->name,
        };
    }
}
