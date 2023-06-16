<?php

namespace Hirodev\BaseDesignPatterns\Console\Commands;

use Hirodev\BaseDesignPatterns\Support\FileGenerator;
use Hirodev\BaseDesignPatterns\Support\GenerateFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Support\Str;

class CreateServiceCommand extends CommandGenerator
{
    /**
     * argumentName
     * @var string
     */
    public $argumentName = 'service';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create a new services class (plugin)";


    public function configure(): void
    {
        $this->addArgument('service', InputArgument::REQUIRED, 'The name of the service class.');
        $this->addOption('interface', 'i', InputOption::VALUE_NONE, 'Flag to create associated Interface', null);
    }

    /**
     * @return string
     */
    protected function getInterfaceTemplateContents(): string
    {
        return (new GenerateFile(__DIR__ ."/stubs/interface.stub", [
            'CLASS_NAMESPACE' => $this->getInterfaceNamespace(),
            'INTERFACE' => $this->getInterfaceNameWithoutNamespace(),
        ]))->render();
    }

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
        // For Interface
        if ($this->option('interface')) {
            $interfacePath = str_replace('\\', '/', $this->interfaceDestinationPath());
            if (!$this->laravel['files']->isDirectory($dir = dirname($interfacePath))) {
                $this->laravel['files']->makeDirectory($dir, 0777, true);
            }

            $interfaceContents = $this->getInterfaceTemplateContents();
        }
        try {
            (new FileGenerator($path, $contents))->generate();

            $this->info("Created : {$path}");

            // For Interface
            if ($this->option('interface')) {
                (new FileGenerator($interfacePath, $interfaceContents))->generate();

                $this->info("Created : {$interfacePath}");
            }
        } catch (\Exception $e) {
            $this->error("File: {$e->getMessage()}");
            return E_ERROR;
        }

        return 0;

    }

    protected function getDestinationFilePath(): string
    {
        return app_path() . $this->resolveNamespace() . '/Services' . '/' . $this->getServiceName() . '.php';

    }

    private function getServiceName()
    {
        $service = Str::studly($this->argument('service'));

        if (Str::contains(strtolower($service), 'service') === false) {
            $service .= 'Service';
        }

        return $service;
    }

    protected function getStubFilePath()
    {
        if ($this->option('interface')) {
            $stub = '/stubs/service-interface.stub';
        } else {
            $stub = '/stubs/service.stub';
        }

        return $stub;
    }

    private function resolveNamespace(): string
    {
        if (str_starts_with($this->getServiceNamespaceFromConfig(), self::APP_PATH)) {
            return str_replace(self::APP_PATH, '', $this->getServiceNamespaceFromConfig());
        }
        return '/' . $this->getServiceNamespaceFromConfig();
    }

    protected function getTemplateContents(): string
    {
        return (new GenerateFile(__DIR__ . $this->getStubFilePath(), [
            'CLASS_NAMESPACE' => $this->getClassNamespace(),
            'INTERFACE_NAMESPACE' => $this->getInterfaceNamespace() . '\\' . $this->getInterfaceNameWithoutNamespace(),
            'CLASS' => $this->getServiceNameWithoutNamespace(),
            'INTERFACE' => $this->getInterfaceNameWithoutNamespace()
        ]))->render();
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
        return "$configNamespace\\Services";
    }


    /**
     * Set Default interface Namespace
     * Override CommandGenerator class method
     * getDefaultInterfaceNamespace
     *
     * @return string
     */
    public function getDefaultInterfaceNamespace(): string
    {
        $configNamespace = $this->getServiceNamespaceFromConfig();
        return "$configNamespace\\Services\\Interfaces";
    }

    private function getServiceNameWithoutNamespace()
    {
        return class_basename($this->getServiceName());
    }

    private function getInterfaceName(): string
    {
        return $this->getServiceName() . "Interface";
    }

    private function getInterfaceNameWithoutNamespace()
    {
        return class_basename($this->getInterfaceName());
    }

    protected function interfaceDestinationPath(): string
    {
        return app_path() . $this->resolveNamespace() . "/Services/Interfaces" . '/' . $this->getInterfaceName() . '.php';

    }
}
