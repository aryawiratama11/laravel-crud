<?php

namespace Wailan\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Repository extends Command
{
    protected $signature = 'wailan:repository {class} {module}';
    protected $description = 'Create a new repository class for the specified module';
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    public function handle()
    {
        $repositoryClassName = $this->argument('class');
        $moduleName = $this->argument('module');

        $datas = explode('/', $repositoryClassName);

        $nameSpace = 'Modules\\' . ucwords($moduleName) . '\Http\Repositories';

        for ($i = 0; $i < count($datas) - 1; $i++) {
            $nameSpace .= '\\' . ucwords($datas[$i]);
        }

        $repositoryClassName = ucwords($datas[$i]);

        $contents =
            '<?php
namespace ' . $nameSpace . ';

use Modules\\' . ($moduleName) . '\Entities\\' . $repositoryClassName . ';

class ' . $repositoryClassName . 'Repository
{
    public static function storeOrUpdate(' . $repositoryClassName . ' $' . strtolower($repositoryClassName) . ', $data)
    {
        // add here

        return $' . strtolower($repositoryClassName) . ';
    }
}';
        $fileName = "${repositoryClassName}Repository.php";
        $moduleDirectory = 'Modules/' . $moduleName;

        $filePath = $nameSpace . '/' . $fileName;

        if ($this->files->isDirectory($moduleDirectory)) {
            if ($this->files->isDirectory($nameSpace)) {
                if ($this->files->isFile($filePath))
                    return $this->error($repositoryClassName . ' already exists!');
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$repositoryClassName created successfully!");
            } else {
                $this->files->makeDirectory($nameSpace, 0777, true, true);
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$repositoryClassName created successfully!");
            }
        } else {
            return $this->error('Module ' . $moduleName . ' not found!');
        }
    }
}
