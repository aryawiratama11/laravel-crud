<?php

namespace Wailan\Crud;

use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    protected $commands = [
        \Wailan\Crud\Commands\CrudController::class,
        \Wailan\Crud\Commands\Repository::class,
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
