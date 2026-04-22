<?php

namespace Syedn\Helper\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeRepositoryCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : The name of the repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // Check for stub at same level as Laravel's default stubs
        $customStub = base_path('stubs/repository.stub');

        if (file_exists($customStub)) {
            return $customStub;
        }

        // Fallback to package stub
        return __DIR__ . '/../../../stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
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

        $modelName = Str::beforeLast($this->getNameInput(), 'Repository');
        $modelClass = $this->rootNamespace() . 'Models\\' . $modelName;

        $stub = str_replace(['{{ namespacedModel }}', '{{ model }}'], [$modelClass, $modelName], $stub);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }
}