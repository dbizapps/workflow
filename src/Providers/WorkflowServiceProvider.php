<?php

namespace dbizapps\Workflow\Providers;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register a controller
        // $this->app->make('dbizapps\Workflow\...');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // register package routes 
        include __DIR__.'/../../routes/routes.php';

        // publish config file
        $this->publishes([
            __DIR__.'/../../config/workflow.php' => config_path('workflow.php')
        ], 'config');
    }
}
