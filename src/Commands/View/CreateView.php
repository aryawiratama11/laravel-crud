<?php

namespace Wailan\Crud\Commands\View;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class CreateView extends Command
{
    use CommandGenerator;

    protected $signature = 'wailan:view-create {class} {module}';
    protected $description = 'Create a new create view for the specified class and module';

    public function handle()
    {
        $this->generator('Modules/' . ucwords($this->argument('module')) . '/resources/views');

        $contents = $this->getTemplateContents();

        $path = strtolower($this->nameSpace .= '/' . $this->className);
        $filename = 'create.blade.php';

        $this->createFile($path, $filename, $contents);
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/CreateView.stub', [
            'MODULENAME' => $this->argument('module'),
            'LOWERMODULENAME' => strtolower($this->argument('module')),
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
            'PLURALLOWERCLASSNAME' => Str::plural(strtolower($this->className))
        ]))->render();
    }
}
