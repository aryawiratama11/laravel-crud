<?php

namespace Wailan\Crud\Commands\Seeder;

use Illuminate\Console\Command;
use Wailan\Crud\Commands\Traits\CommandGenerator;
use Wailan\Crud\Services\Stub;

class PermissionSeeder extends Command
{
    use CommandGenerator;

    protected $signature = 'concrete:permission {class} {module}';
    protected $description = 'Create a new permission seeder for the specified class';

    public function handle()
    {
        $this->generator('database/seeders');

        $contents = $this->getTemplateContents();

        $path = 'database/seeders';
        $filename = $this->className . 'PermissionSeeder.php';

        $this->createFile($path, $filename, $contents);
    }

    protected function getTemplateContents(): string
    {
        $colors = [
            'Black' => '#000000',
            'Blue' => '#0000FF',
            'Gray' => '#808080',
            'Green' => '#008000',
            'Purple' => '#800080',
            'Red' => '#FF0000',
            'White' => '#FFFFFF',
            'AliceBlue' => '#F0F8FF',
            'Coral' => '#FF7F50',
            'FireBrick' => '#B22222',
            'HotPink' => '#FF69B4',
            'LemonChiffon' => '#FFFACD',
        ];

        $name = $this->choice('Pick a color for your permission', $colors);

        return (new Stub('/Seeder.stub', [
            'CLASSNAME' => $this->className,
            'LOWERCLASSNAME' => strtolower($this->className),
            'COLOR' => $colors[$name]
        ]))->render();
    }
}
