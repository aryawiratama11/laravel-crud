<?php

namespace Wailan\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class CrudController extends Command
{
    protected $signature = 'wailan:crud {controller} {module}';
    protected $description = 'Create a new crud controller class for the specified module';
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    public function handle()
    {
        $crudController = $this->argument('controller');
        $moduleName = $this->argument('module');

        $datas = explode('/', $crudController);

        $nameSpace = 'Modules\\' . $moduleName . '\http\Controllers';
        $classFolder = '';
        $view = '';

        for ($i = 0; $i < count($datas) - 1; $i++) {
            $classFolder .= '\\' . $datas[$i];
            $view .=  strtolower($datas[$i]) . '.';
        }

        $crudController = $datas[$i];
        $nameSpace .= $classFolder;

        $lowerCrudController = strtolower($crudController);

        $contents =
            '<?php

namespace ' . $nameSpace . ';

use Illuminate\Routing\Controller;
use Modules\\' . $moduleName . '\Entities\\' . $crudController . ';
use Modules\\' . $moduleName . '\http\Repositories' . $classFolder . '\\' . $crudController . 'Repository;
use Modules\\' . $moduleName . '\Http\Requests\Store' . $crudController . 'Request;
use Modules\\' . $moduleName . '\Http\Requests\Update' . $crudController . 'Request;

class ' . $crudController . 'Controller extends Controller
{
    public function index()
    {
        $' . Str::plural($lowerCrudController) . ' = ' . $crudController . '::paginate(5);
        return view("' . strtolower($moduleName) . '::' . $view . $lowerCrudController . '.index", compact("' . Str::plural($lowerCrudController) . '"));
    }

    public function create()
    {
        return view("' . strtolower($moduleName) . '::' . $view . $lowerCrudController . '.create");
    }

    public function store(Store' . $crudController . 'Request $request)
    {
        $' . $lowerCrudController . ' = new ' . $crudController . '();
        $' . $lowerCrudController . ' = ' . $crudController . 'Repository::storeOrUpdate($' . $lowerCrudController . ', $request);

        return redirect()->route("' . strtolower($moduleName)  . '.' . $view . $lowerCrudController . '.index")->with("success", $' . $lowerCrudController . '->name . " Created");
    }

    public function show(' . $crudController . ' $' . $lowerCrudController . ')
    {
        return view("' . strtolower($moduleName) . '::' . $view . $lowerCrudController . '.show", compact("' . $lowerCrudController . '"));
    }

    public function edit(' . $crudController . ' $' . $lowerCrudController . ')
    {
        return view("' . strtolower($moduleName) . '::' . $view . $lowerCrudController . '.edit", compact("' . $lowerCrudController . '"));
    }

    public function update(Update' . $crudController . 'Request $request, ' . $crudController . ' $' . $lowerCrudController . ')
    {
        $' . $lowerCrudController . ' = ' . $crudController . 'Repository::storeOrUpdate($' . $lowerCrudController . ', $request);

        return redirect()->route("' . strtolower($moduleName)  . '.' . $view . $lowerCrudController . '.index")->with("success", $' . $lowerCrudController . '->name . " Updated");
    }

    public function destroy(' . $crudController . ' $' . $lowerCrudController . ')
    {
        $' . $lowerCrudController . '->delete();

        return redirect()->route("' . strtolower($moduleName)  . '.' . $view . $lowerCrudController . '.index")->with("success", $' . $lowerCrudController . '->name . " Deleted!");
    }
}
';
        $fileName = $crudController . 'Controller.php';

        $filePath = $nameSpace . '/' . $fileName;

        if ($this->files->isDirectory('Modules/' . $moduleName)) {
            if ($this->files->isDirectory($nameSpace)) {
                if ($this->files->isFile($filePath))
                    return $this->error($crudController . ' already exists!');
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->callOther($crudController, $this->argument('module'));
                $this->info("$crudController created successfully!");
            } else {
                $this->files->makeDirectory($nameSpace, 0777, true, true);
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->callOther($crudController, $this->argument('module'));
                $this->info("$crudController created successfully!");
            }
        } else {
            return $this->error('Module ' . $moduleName . ' not found!');
        }
    }

    public function callOther($crudController, $moduleName)
    {
        $this->call('module:make-model', [
            'model' => $crudController,
            'module' => $moduleName
        ]);
        $this->call('wailan:repository', [
            'class' => $this->argument('controller'),
            'module' => $moduleName
        ]);
        $this->call('module:make-request', [
            'name' => 'Store' . $crudController . 'Request',
            'module' => $moduleName
        ]);
        $this->call('module:make-request', [
            'name' => 'Update' . $crudController . 'Request',
            'module' => $moduleName
        ]);
        $this->call('make:migration', [
            'name' => 'create' . Str::plural($crudController) . '_table'
        ]);

        if ($this->confirm('Do you want to generate CRUD View?', true)) {
            $this->call('wailan:view-create', [
                'class' => $this->argument('controller'),
                'module' => $this->argument('module')
            ]);
            $this->call('wailan:view-index', [
                'class' => $this->argument('controller'),
                'module' => $this->argument('module')
            ]);
            $this->call('wailan:view-edit', [
                'class' => $this->argument('controller'),
                'module' => $this->argument('module')
            ]);
            $this->call('wailan:view-show', [
                'class' => $this->argument('controller'),
                'module' => $this->argument('module')
            ]);
        }
    }
}
