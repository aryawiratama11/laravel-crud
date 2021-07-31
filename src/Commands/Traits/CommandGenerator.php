<?php

namespace Wailan\Crud\Commands\Traits;

use Wailan\Crud\Services\Stub;

trait CommandGenerator
{
    protected $className = '';
    protected $classFolder = '';
    protected $nameSpace = '';
    protected $view = '';
    protected $fileName = '';

    public function createFile($filePath, $contents)
    {
        if ($this->files->isDirectory('Modules/' . $this->argument('module'))) {
            if ($this->files->isDirectory($this->nameSpace)) {
                if ($this->files->isFile($this->fileName))
                    return $this->error($this->className . ' already exists!');
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$this->className created successfully!");
            } else {
                $this->files->makeDirectory($this->nameSpace, 0777, true, true);
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$this->className created successfully!");
            }
        } else {
            return $this->error('Module ' . $this->argument('module') . ' not found!');
        }
    }

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

    protected function generateFilePath($filename)
    {
        $this->fileName = $this->nameSpace . '/' . $this->className . $filename;
        return $this->fileName;
    }
}
