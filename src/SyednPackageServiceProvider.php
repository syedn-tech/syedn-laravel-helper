<?php

namespace Syedn\Helper;

use Illuminate\Support\ServiceProvider;

class SyednPackageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
        $this->autoInstallStubs();
    }

    public function register()
    {
        // Bind classes to the container
    }

        /**
     * Register custom commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Syedn\Helper\Console\Commands\MakeRepositoryCommand::class,
                \Syedn\Helper\Console\Commands\MakeServiceCommand::class,
                \Syedn\Helper\Console\Commands\MakeConstantCommand::class,
            ]);
        }
    }

    /**
     * Auto-install stubs when package is installed.
     */
    protected function autoInstallStubs(): void
    {
        if ($this->app->runningInConsole()) {
            $this->configureStubPathToPackage();
        }
    }

    /**
     * Configure stubs at same level as Laravel defaults via direct copying.
     */
    protected function configureStubPathToPackage(): void
    {
        $stubsPath = base_path('stubs');
        $packageStubsPath = __DIR__ . '/../stubs';

        // Create base stubs directory
        if (!file_exists($stubsPath)) {
            mkdir($stubsPath, 0755, true);
        }

        $allStubMappings = [
            // Laravel overrides
            'model.stub'                => 'model.stub',
            'controller.stub'           => 'controller.stub',       // Standard Web/Backend
            'controller.api.stub'       => 'controller.api.stub',   // NEW: Dedicated API stub
            'controller.plain.stub'     => 'controller.plain.stub',
            'controller.invokable.stub' => 'controller.invokable.stub',
            
            // Package custom stubs
            'repository.stub'           => 'repository.stub',
            'service.stub'              => 'service.stub',
            'constant.stub'             => 'constant.stub',
            'exception.stub'            => 'exception.stub',
            'trait.stub'                => 'trait.stub',
        ];

        foreach ($allStubMappings as $consumerStub => $packageStub) {
            $consumerStubPath = $stubsPath . '/' . $consumerStub;
            $packageStubPath = $packageStubsPath . '/' . $packageStub;

            if (file_exists($packageStubPath)) {
                // Note: If you want to avoid overwriting user customizations, 
                // you could wrap this in a !file_exists($consumerStubPath) check.
                copy($packageStubPath, $consumerStubPath);
            }
        }
    }
}