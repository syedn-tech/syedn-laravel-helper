<?php

namespace Syedn\Helper\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // Check for stub at same level as Laravel's default stubs
        $customStub = base_path('stubs/service.stub');

        if (file_exists($customStub)) {
            return $customStub;
        }

        // Fallback to package stub
        return __DIR__ . '/../../../stubs/service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $repositoryName = Str::beforeLast($this->getNameInput(), 'Service') . 'Repository';
        $repositoryClass = $this->rootNamespace() . 'Repositories\\' . $repositoryName;

        $stub = str_replace(['{{ namespacedRepository }}', '{{ repository }}'], [$repositoryClass, $repositoryName], $stub);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
}