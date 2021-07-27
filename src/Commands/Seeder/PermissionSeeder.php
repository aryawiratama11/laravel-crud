<?php

namespace Wailan\Crud\Commands\Seeder;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PermissionSeeder extends Command
{
    protected $signature = 'wailan:permission {class}';
    protected $description = 'Create a new permission seeder for the specified class';
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    public function handle()
    {
        $class = $this->argument('class');

        $datas = explode('/', $class);
        $path = '';
        for ($i = 0; $i < count($datas) - 1; $i++) {
            $path .= '/' . $datas[$i];
        }
        $class = $datas[$i];

        $contents =
            '<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ' . $class . 'PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "' . strtolower($class) . '-access",
            "' . strtolower($class) . '-create",
            "' . strtolower($class) . '-store",
            "' . strtolower($class) . '-edit",
            "' . strtolower($class) . '-show",
            "' . strtolower($class) . '-update",
            "' . strtolower($class) . '-delete",
        ];

        $permissionsColor = [
            "#563d7c",
            "#563d7c",
            "#563d7c",
            "#563d7c",
            "#563d7c",
            "#563d7c",
            "#563d7c",
        ];

        for ($i = 0; $i < count($permissions); $i++) {
            Permission::create([
                "name" => $permissions[$i],
                "color" => $permissionsColor[$i]
            ]);
        }
    }
}
            ';
        $basePath = 'database/seeders';
        $fileName = $class . "PermissionSeeder.php";
        $filePath = $basePath . '/' . $fileName;

        if ($this->files->isDirectory($basePath)) {
            if ($this->files->isFile($filePath))
                return $this->error($class . ' permission seeder already exists!');
            if (!$this->files->put($filePath, $contents))
                return $this->error('failed!');
            $this->info("$class permission seeder created successfully!");
        } else {
            $this->files->makeDirectory($basePath, 0777, true, true);
            if (!$this->files->put($filePath, $contents))
                return $this->error('failed!');
            $this->info("$class permission seeder created successfully!");
        }
    }
}
