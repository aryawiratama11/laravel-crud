<?php

namespace Wailan\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    protected $commands = [
        \Wailan\Crud\Commands\CrudController::class,
        \Wailan\Crud\Commands\Repository::class,
        \Wailan\Crud\Commands\IndexView::class,
        \Wailan\Crud\Commands\CreateView::class,
        \Wailan\Crud\Commands\EditView::class,
        \Wailan\Crud\Commands\ShowView::class,
    ];

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    public function register()
    {
        $this->commands($this->commands);
    }
}
