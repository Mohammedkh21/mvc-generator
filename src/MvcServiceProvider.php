<?php

namespace Mohammedkh21\MvcGenerator;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Mohammedkh21\MvcGenerator\Commands\MvcGeneratorCommand;
use Mohammedkh21\MvcGenerator\Services\MvcGeneratorService;


class MvcServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {

        $this->commands([
            MvcGeneratorCommand::class
        ]);

        $this->app->bind(MvcGeneratorService::class);
    }
}


