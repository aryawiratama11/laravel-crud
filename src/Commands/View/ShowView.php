<?php

namespace Wailan\Crud\Commands\View;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ShowView extends Command
{
    protected $signature = 'wailan:view-show {class} {module}';
    protected $description = 'Create a new show view for the specified class and module';
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
            '@extends("' . strtolower($moduleName) . '::layouts.master")

@section("content")

@endsection
            ';
        $moduleDirectory = 'Modules/' . $moduleName;
        $nameSpace = strtolower($moduleDirectory . '/resources/views' . $path . '/' . $class);
        $fileName = "show.blade.php";
        $filePath = strtolower($nameSpace . '/' . $fileName);

        if ($this->files->isDirectory($moduleDirectory)) {
            if ($this->files->isDirectory($nameSpace)) {
                if ($this->files->isFile($filePath))
                    return $this->error($class . ' show view already exists!');
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$class show view created successfully!");
            } else {
                $this->files->makeDirectory($nameSpace, 0777, true, true);
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$class show view created successfully!");
            }
        } else {
            return $this->error('Module ' . $moduleName . ' not found!');
        }
    }
}
