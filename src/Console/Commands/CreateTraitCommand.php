<?php

namespace Hirodev\BaseDesignPatterns\Console\Commands;

use Hirodev\BaseDesignPatterns\Support\FileGenerator;
use Hirodev\BaseDesignPatterns\Support\GenerateFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;

class CreateTraitCommand extends CommandGenerator
{
    /**
     * argumentName
     * @var string
     */
    public $argumentName = 'trait';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new trait (plugin)";

    /**
     * @return int
     */
    public function handle()
    {
        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();
        try {
            (new FileGenerator($path, $contents))->generate();
            $this->info("Created : {$path}");

        } catch (\Exception $e) {
            $this->error("File: {$e->getMessage()}");
            return E_ERROR;
        }

        return 0;
    }

    /**
     * @return string
     */
    protected function getTemplateContents(): string
    {
        $this->comment(Inspiring::quote());
        return (new GenerateFile(__DIR__ . $this->getStubFilePath(), [
            'CLASS_NAMESPACE' => $this->getClassNamespace(),
            'CLASS' => $this->getTraitNameWithoutNamespace(),
        ]))->render();
    }

    /**
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        return app_path() . '/Traits' . '/' . $this->getTraitName() . '.php';
    }

    public function configure(): void
    {
        $this->addArgument('trait', InputArgument::REQUIRED, 'The name of the trait.');
    }

    private function getTraitName()
    {
        return Str::studly($this->argument('trait'));
    }

    private function getTraitNameWithoutNamespace()
    {
        return class_basename($this->getTraitName());
    }

    private function getStubFilePath()
    {
        return '/stubs/traits.stub';
    }

    /**
     * Set Default Namespace
     * Override CommandGenerator class method
     * getDefaultNamespace
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        $configNamespace = $this->getServiceNamespaceFromConfig();
        return "$configNamespace\\Traits";
    }
}
