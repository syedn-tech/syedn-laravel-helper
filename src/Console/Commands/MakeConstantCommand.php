<?php

namespace Syedn\Helper\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeConstantCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:constant {name : The name of the constant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new constant class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Constant';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        // Check for stub at same level as Laravel's default stubs
        $customStub = base_path('stubs/constant.stub');

        if (file_exists($customStub)) {
            return $customStub;
        }

        // Fallback to package stub
        return __DIR__ . '/../../../stubs/constant.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Constants';
    }
}