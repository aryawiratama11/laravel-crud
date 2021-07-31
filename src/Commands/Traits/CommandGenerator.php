<?php

namespace Wailan\Crud\Commands\Traits;

use Illuminate\Filesystem\Filesystem;

trait CommandGenerator
{
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    protected $className = '';
    protected $classFolder = '';
    protected $nameSpace = '';
    protected $view = '';
    protected $fileName = '';

    public function createFile($pathToFile, $fileName, $contents)
    {
        if ($this->files->isDirectory('Modules/' . $this->argument('module'))) {
            if ($this->files->isDirectory($pathToFile)) {
                if ($this->files->isFile($pathToFile . '/' . $fileName))
                    return $this->error($pathToFile . '/' . $fileName . ' already exists!');
                if (!$this->files->put($pathToFile . '/' . $fileName, $contents))
                    return $this->error('failed!');
                $this->info("Created : $pathToFile/$fileName");
            } else {
                $this->files->makeDirectory($pathToFile, 0777, true, true);
                if (!$this->files->put($pathToFile . '/' . $fileName, $contents))
                    return $this->error('failed!');
                $this->info("Created : $pathToFile/$fileName");
            }
        } else {
            return $this->error('Module ' . $this->argument('module') . ' not found!');
        }
    }

    // This function generate namespace, classname, classfolder, and viewname
    protected function generator($defaultNamespace)
    {
        $this->nameSpace = $defaultNamespace;

        $datas = explode('/', $this->argument('class'));

        for ($i = 0; $i < count($datas) - 1; $i++) {
            $this->classFolder .= '\\' . ucwords($datas[$i]);
            $this->view .=  strtolower($datas[$i]) . '.';
        }

        $this->nameSpace .= $this->classFolder;
        $this->className = ucwords($datas[$i]);
    }
}
