<?php

namespace Hirodev\BaseDesignPatterns;

use Hirodev\BaseDesignPatterns\Console\Commands\CreateRepositoryCommand;
use Hirodev\BaseDesignPatterns\Console\Commands\CreateServiceCommand;
use Hirodev\BaseDesignPatterns\Console\Commands\CreateTraitCommand;
use Illuminate\Support\ServiceProvider;

class CommandProvider extends ServiceProvider
{
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
    }
}
