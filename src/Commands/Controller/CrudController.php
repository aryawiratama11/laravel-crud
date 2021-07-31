<?php

namespace Wailan\Crud\Commands\Controller;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class CrudController extends Command
{
    use CommandGenerator;

    protected $signature = 'wailan:crud {class} {module}';
    protected $description = 'Create a new crud controller class for the specified module';

    public function handle()
    {
        $this->generator('Modules\\' . ucwords($this->argument('module')) . '\Http\Controllers');

        $contents = $this->getTemplateContents();
        $filePath = strtolower($this->nameSpace);
        $fileName = $this->className . 'Controller.php';

        $this->createFile($filePath, $fileName, $contents);
        $this->callOther($this->className, $this->argument('module'));
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/controller.stub', [
            'NAMESPACE' => $this->nameSpace,
            'CLASSFOLDER' => $this->classFolder,
            'MODULENAME' => $this->argument('module'),
            'LOWERMODULENAME' => strtolower($this->argument('module')),
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
            'PLURALLOWERCLASSNAME' => Str::plural(strtolower($this->className)),
            'VIEW' => $this->view
        ]))->render();
    }

    public function callOther()
    {
        $this->info('Generating model');
        $this->call('module:make-model', [
            'model' => $this->className,
            'module' => $this->argument('module')
        ]);

        $this->info('Generating repository');
        $this->call('wailan:repository', [
            'class' => $this->argument('class'),
            'module' => $this->argument('module')
        ]);

        $this->info('Generating request');
        $this->call('module:make-request', [
            'name' => 'Store' . $this->className . 'Request',
            'module' => $this->argument('module')
        ]);
        $this->call('module:make-request', [
            'name' => 'Update' . $this->className . 'Request',
            'module' => $this->argument('module')
        ]);

        $this->info('Updating route');
        $this->call('wailan:route-web', [
            'class' => $this->className,
            'module' => $this->argument('module')
        ]);

        $this->info('Generating permission');
        $this->call('wailan:permission', [
            'class' => $this->argument('class'),
            'module' => $this->argument('module')
        ]);

        $this->info('Generating migration');
        $this->info('Please wait untill migration finish');
        $this->call('make:migration', [
            'name' => 'create' . Str::plural($this->className) . '_table'
        ]);

        if ($this->confirm('Do you want to generate CRUD View?', true)) {
            $this->info('Generating CRUD View');
            $this->call('wailan:view-create', [
                'class' => $this->argument('class'),
                'module' => $this->argument('module')
            ]);
            $this->call('wailan:view-index', [
                'class' => $this->argument('class'),
                'module' => $this->argument('module')
            ]);
            $this->call('wailan:view-edit', [
                'class' => $this->argument('class'),
                'module' => $this->argument('module')
            ]);
            $this->call('wailan:view-show', [
                'class' => $this->argument('class'),
                'module' => $this->argument('module')
            ]);
        }

        $this->line('========================================');
        $this->info('CRUD installation completed');
        $this->info('Dont forget to');
        $this->info('Setup database migration and run');
        $this->info('php artisan migrate --seed');
        $this->line('========================================');
    }
}
