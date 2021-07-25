<?php

namespace Wailan\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class EditView extends Command
{
    protected $signature = 'wailan:view-edit {class} {module}';
    protected $description = 'Create a new edit view for the specified class and module';
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
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route("' . strtolower($moduleName) . '.' . strtolower($class) . '.update", ["' . strtolower($class) . '" => $' . strtolower($class) . ']) }}" method="POST">
                @csrf
                <div class="bg-white border rounded p-4">
                    <div class="form-group">
                        <label for="name">{{ __("Name") }}</label>
                        <input type="name" name="name" class="form-control @error("name") is-invalid @enderror" id="name"
                            placeholder="example name" value="{{ old("name") }}">
                        @error("name")
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect1">Example select</label>
                        <select class="form-control" name="exampleFormControlSelect1" id="exampleFormControlSelect1" value="{{ old("exampleFormControlSelect1") }}">>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Example multiple select</label>
                        <select multiple class="form-control" name="exampleFormControlSelect2" id="exampleFormControlSelect2" value="{{ old("exampleFormControlSelect2") }}">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Example textarea</label>
                        <textarea class="form-control" name="exampleFormControlTextarea1" id="exampleFormControlTextarea1" rows="3">{{ old("exampleFormControlTextarea1") }}</textarea>
                    </div>
                </div>
                <button class="btn btn-sm btn-primary my-2 w-100 text-bold" type="submit">
                    {{ __("Submit") }}
                </button>
            </form>
        </div>
    </div>
@endsection
            ';
            $moduleDirectory = 'Modules/' . $moduleName;
            $nameSpace = strtolower($moduleDirectory . '/resources/views' . $path . '/' . $class);
            $fileName = "edit.blade.php";
            $filePath = strtolower($nameSpace . '/' . $fileName);

            if ($this->files->isDirectory($moduleDirectory)) {
                if ($this->files->isDirectory($nameSpace)) {
                    if ($this->files->isFile($filePath))
                        return $this->error($class . ' edit view already exists!');
                    if (!$this->files->put($filePath, $contents))
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
