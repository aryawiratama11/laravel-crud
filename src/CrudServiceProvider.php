<?php

namespace Wailan\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    protected $commands = [
        \Wailan\Crud\Commands\Controller\CrudController::class,
        \Wailan\Crud\Commands\Repositories\Repository::class,
        \Wailan\Crud\Commands\View\IndexView::class,
        \Wailan\Crud\Commands\View\CreateView::class,
        \Wailan\Crud\Commands\View\EditView::class,
        \Wailan\Crud\Commands\View\ShowView::class,
        \Wailan\Crud\Commands\Seeder\PermissionSeeder::class,
        \Wailan\Crud\Commands\Routes\Web::class,
    ];

    public function boot()
    {
        // $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    public function register()
    {
        $this->commands($this->commands);
    }
}
