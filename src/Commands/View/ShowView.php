<?php

namespace Wailan\Crud\Commands\View;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class ShowView extends Command
{
    use CommandGenerator;

    protected $signature = 'wailan:view-show {class} {module} {master}';
    protected $description = 'Create a new show view for the specified class and module';

    public function handle()
    {
        $this->generator('Modules/' . ucwords($this->argument('module')) . '/resources/views');

        $contents = $this->getTemplateContents();

        $path = strtolower($this->nameSpace .= '/' . $this->className);
        $filename = 'show.blade.php';

        $this->createFile($path, $filename, $contents);
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/ShowView.stub', [
            'MODULENAME' => $this->argument('module'),
            'LOWERMODULENAME' => strtolower($this->argument('module')),
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
            'PLURALLOWERCLASSNAME' => Str::plural(strtolower($this->className)),
            'MASTERVIEW' => $this->argument('master')
        ]))->render();
    }
}
