<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Support\Console\Concerns\WithDomainControllerContext;
use Support\Console\Enums\Endpoints;
use Support\Console\Enums\EndpointType;

class MakeController extends ControllerMakeCommand
{
    use WithDomainControllerContext;

    protected function getStub()
    {
        return match (true) {
            Endpoints::tryFrom(strtolower($this->endpoint))?->isSingleResource(), $this->endpointType === EndpointType::Action->value => __DIR__.'/stubs/controller.single-resource.stub',
            default => __DIR__.'/stubs/controller.stub',
        };
    }

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

        foreach ($this->endpoints as $endpoint) {
            $this->setEndpoint($endpoint);

            parent::handle();

            $this->call(MakeRequest::class, [
                '--api-version' => $this->apiVersion,
                '--domain-model' => $this->domainModel,
                '--action' => $this->actionName,
                '--type' => $this->endpointType,
                '--endpoint' => $this->endpoint,
            ]);

            $this->call(MakeTestForController::class, [
                '--api-version' => $this->apiVersion,
                '--domain-model' => $this->domainModel,
                '--action' => $this->actionName,
                '--type' => $this->endpointType,
                '--endpoint' => $this->endpoint,
            ]);
        }

        return null;
    }

    protected function getNameInput()
    {
        return 'Controller';
    }
}
