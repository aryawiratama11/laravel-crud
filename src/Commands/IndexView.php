<?php

namespace Wailan\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class IndexView extends Command
{
    protected $signature = 'wailan:view-index {class} {module}';
    protected $description = 'Create a new index view for the specified class and module';
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
            <div class="row mb-3">
                <div class="col-sm">
                    @can("' . strtolower($moduleName) . '-create")
                        <a href="{{ route("' . strtolower($moduleName) . '.'.strtolower($class).'.create") }}" class="btn btn-light border">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endcan
                </div>
                <div class="col-sm">
                    <form>
                        <div class="input-group rounded">
                            <input type="search" class="form-control rounded" placeholder="Search" aria-label="Search"
                                aria-describedby="search-addon" name="search" value="{{ request()->input("search") }}" />
                            <button class="input-group-text border-0" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12 bg-white p-0 border rounded shadow-sm">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($' . strtolower(Str::plural($class)) . ' as $' . strtolower($class) . ')
                            <tr>
                                <th scope="row">
                                    {{ ($' . strtolower(Str::plural($class)) . '->currentpage() - 1) * $' . strtolower(Str::plural($class)) . '->perpage() + $loop->index + 1 }}
                                </th>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm" type="button" data-boundary="viewport"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can("' . strtolower($moduleName) . '-access")
                                                <a class="dropdown-item"
                                                    href="{{ route("' . strtolower($moduleName) . '.' . strtolower($class) . '.show", ["' . strtolower($class) . '" => $' . strtolower($class) . ']) }}">
                                                    Show
                                                </a>
                                            @endcan
                                            @can("' . strtolower($moduleName) . '-edit")
                                            <a class="dropdown-item"
                                                href="{{ route("' . strtolower($moduleName) . '.' . strtolower($class) . '.edit", ["' . strtolower($class) . '" => $' . strtolower($class) . ']) }}">
                                                Edit
                                            </a>
                                            @endcan
                                            @can("' . strtolower($moduleName) . '-delete")
                                            <div class="dropdown-divider"></div>
                                            <form onsubmit="return confirm("Are you sure?")"
                                                action="{{ route("' . strtolower($moduleName) . '.' . strtolower($class) . '.destroy", ["' . strtolower($class) . '" => $' . strtolower($class) . ']) }}"
                                                method="POST">
                                                @method("DELETE")
                                                @csrf
                                                <button class="dropdown-item" type="submit">Delete</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No user found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="row justify-content-center">
                {{ $' . strtolower(Str::plural($class)) . '->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
@endsection

            ';
        $moduleDirectory = 'Modules/' . $moduleName;
        $nameSpace = strtolower($moduleDirectory . '/resources/views' . $path . '/' . $class);
        $fileName = "index.blade.php";
        $filePath = strtolower($nameSpace . '/' . $fileName);

        if ($this->files->isDirectory($moduleDirectory)) {
            if ($this->files->isDirectory($nameSpace)) {
                if ($this->files->isFile($filePath))
                    return $this->error($class . ' index view already exists!');
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$class index view created successfully!");
            } else {
                $this->files->makeDirectory($nameSpace, 0777, true, true);
                if (!$this->files->put($filePath, $contents))
                    return $this->error('failed!');
                $this->info("$class index view created successfully!");
            }
        } else {
            return $this->error('Module ' . $moduleName . ' not found!');
        }
    }
}
