<?php

namespace Wailan\Crud\Commands\View;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class IndexView extends Command
{
    use CommandGenerator;
    protected $signature = 'wailan:view-index {class} {module} {master}';
    protected $description = 'Create a new index view for the specified class and module';

    public function handle()
    {
        $this->generator('Modules/' . ucwords($this->argument('module')) . '/resources/views');

        $contents = $this->getTemplateContents();

        $path = strtolower($this->nameSpace .= '/' . $this->className);
        $filename = 'index.blade.php';

        $this->createFile($path, $filename, $contents);
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/IndexView.stub', [
            'MODULENAME' => $this->argument('module'),
            'LOWERMODULENAME' => strtolower($this->argument('module')),
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
            'PLURALLOWERCLASSNAME' => Str::plural(strtolower($this->className)),
            'MASTERVIEW' => $this->argument('master')
        ]))->render();
    }
}
