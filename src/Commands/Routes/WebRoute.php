<?php

namespace Wailan\Crud\Commands\Routes;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class WebRoute extends Command
{
    protected $signature = 'wailan:route-web {class} {module}';
    protected $description = 'Modify a web routes for the specified class and module';
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    public function handle()
    {
        $class = $this->argument('class');
        $moduleName = $this->argument('module');

        $datas = explode('/', $class);
        $path = '';
        for ($i = 0; $i < count($datas) - 1; $i++) {
            $path .= '/' . $datas[$i];
        }
        $class = $datas[$i];

        $contents =
            '
Route::group(["as" => "' . strtolower($moduleName) . '."], function () {
    Route::resource("' . strtolower($class) . '", ' . ucwords($class) . 'Controller::class);
});
            ';

        $moduleDirectory = 'Modules/' . $moduleName;
        $nameSpace = strtolower($moduleDirectory . '/routes');
        $fileName = "web.php";
        $filePath = strtolower($nameSpace . '/' . $fileName);

        if ($this->files->isDirectory($moduleDirectory)) {
            if ($this->files->isDirectory($nameSpace)) {
                $newContent = file_get_contents($filePath) . $contents;
                if (!$this->files->put($filePath, $newContent))
                    return $this->error('failed!');
                $this->info("$class edit view created successfully!");
            } else {
                $this->files->makeDirectory($nameSpace, 0777, true, true);
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$class edit view created successfully!");
            }
        } else {
            return $this->error('Module ' . $moduleName . ' not found!');
        }
    }
}
