<?php

namespace Hirodev\BaseDesignPatterns;

use Hirodev\BaseDesignPatterns\Console\Commands\CreateRepositoryCommand;
use Hirodev\BaseDesignPatterns\Console\Commands\CreateServiceCommand;
use Hirodev\BaseDesignPatterns\Console\Commands\CreateTraitCommand;
use Illuminate\Support\ServiceProvider;

class CommandProvider extends ServiceProvider
{
    const APP_PATH = 'App';

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            CreateRepositoryCommand::class,
            CreateServiceCommand::class,
            CreateTraitCommand::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/command.php' => config_path('command.php'),
        ], 'config');

        // Publish repository file
        $this->publishes([
            __DIR__ . '/Support/Base/Repository/AbstractModelRepositoryInterface.php' => $this->repositoryDestinationPath('AbstractModelRepositoryInterface', true),
            __DIR__ . '/Support/Base/Repository/AbstractModelRepository.php' => $this->repositoryDestinationPath('AbstractModelRepositoryInterface')
        ], 'repository');
    }

    protected function repositoryDestinationPath($name, $isInterface = false): string
    {
        $interface = $isInterface ? 'Interfaces/' : '';
        return app_path() . $this->resolveRepositoryNamespace() . "/Repositories/" . $interface . "Base/" . $name . '.php';
    }

    private function resolveRepositoryNamespace(): string
    {
        if (str_starts_with($this->getRepositoryNamespaceFromConfig(), self::APP_PATH)) {
            return str_replace(self::APP_PATH, '', $this->getRepositoryNamespaceFromConfig());
        }
        return '/' . $this->getRepositoryNamespaceFromConfig();
    }

    private function getRepositoryNamespaceFromConfig()
    {
        return config('command.repository-namespace') ?? 'App';
    }
}
