<?php

namespace Wailan\Crud\Commands\Repositories;

use Illuminate\Console\Command;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class Repository extends Command
{
    use CommandGenerator;

    protected $signature = 'wailan:repository {class} {module}';
    protected $description = 'Create a new repository class for the specified module';

    public function handle()
    {
        $this->generator('Modules\\' . ucwords($this->argument('module')) . '\Http\Repositories');

        $contents = $this->getTemplateContents();
        $filePath = strtolower($this->nameSpace);
        $fileName = $this->className.'Repository.php';

        $this->createFile($filePath,$fileName, $contents);
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/repository.stub', [
            'NAMESPACE' => $this->nameSpace,
            'MODULENAME' => $this->argument('module'),
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
        ]))->render();
    }
}
