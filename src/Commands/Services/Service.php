<?php

namespace Wailan\Crud\Commands\Services;

use Illuminate\Console\Command;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class Service extends Command
{
    use CommandGenerator;

    protected $signature = 'concrete:service {class} {module}';
    protected $description = 'Create a new service class for the specified module';

    public function handle()
    {
        $this->generator('Modules\\' . ucwords($this->argument('module')) . '\Http\Services');

        $contents = $this->getTemplateContents();
        $filePath = $this->nameSpace;
        $fileName = $this->className . 'Service.php';

        $this->createFile($filePath, $fileName, $contents);
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/service.stub', [
            'NAMESPACE' => $this->nameSpace,
            'MODULENAME' => $this->argument('module'),
            'LOWERMODULENAME' => strtolower($this->argument('module')),
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
        ]))->render();
    }
}
