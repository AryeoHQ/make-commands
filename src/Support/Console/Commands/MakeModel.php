<?php

declare(strict_types=1);

namespace Support\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Support\Console\Concerns\WithDomainModelContext;

class MakeModel extends ModelMakeCommand
{
    use WithDomainModelContext;

    public function getStub()
    {
        return __DIR__.'/stubs/model.stub';
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
        $this->domainModel = $this->getNameInput();

        if ($this->alreadyExists($this->getNameInput())) {
            $this->components->error(Str::plural($this->getNameInput()).' domain model already exists.');

            return false;
        }

        parent::handle();

        $this->call(MakeFactory::class, [
            'name' => 'Factory',
            '--domain-model' => $this->getNameInput(),
        ]);

        $this->call(MakeBuilder::class, [
            'name' => 'Builder',
            '--domain-model' => $this->getNameInput(),
        ]);

        $this->call(MakeProvider::class, [
            'name' => 'ServiceProvider',
            '--domain-model' => $this->getNameInput(),
        ]);

        $this->call(MakeTestForDomainModel::class, [
            'name' => $this->getNameInput().'Test',
            '--domain-model' => $this->getNameInput(),
        ]);

        $this->call(MakeCollection::class, [
            'name' => Str::plural($this->getNameInput()),
            '--domain-model' => $this->getNameInput(),
        ]);

        foreach ($this->observableEvents() as $event) {
            $this->call(MakeEvent::class, [
                'name' => $this->getEventClassName($event),
                '--domain-model' => $this->getNameInput(),
            ]);
        }

        return null;
    }
}
