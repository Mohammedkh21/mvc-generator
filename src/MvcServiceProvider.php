<?php

namespace Mohammedkh21\MvcGenerator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class MvcServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {

        $this->commands([
            MvcGenerator::class,
        ]);



    }
}

class MvcGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:MvcGenerator {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make MVC';

    /**
     * Execute the console command.
     */
    public function handle()
    {

         $name = $this->argument('name');

        // Generate Model
        Artisan::call('make:model', ['name' => $name]);

        // Generate Migration
        Artisan::call('make:migration', [
            'name' => 'create_' . Str::plural(strtolower($name)) . '_table',
            '--create' => Str::plural(strtolower($name)),
        ]);

        // Generate Seeder
        Artisan::call('make:seeder', ['name' => $name . 'Seeder']);

        // Generate Resource Controller
        Artisan::call('make:controller', [
            'name' => $name . 'Controller',
            '--resource' => true,
        ]);

        // Generate Resource Route
        $routeContents = "Route::resource('" . strtolower(Str::plural($name)) . "', \App\Http\Controllers\\" . $name . "Controller::class);\n";
        file_put_contents(base_path('routes/web.php'), $routeContents, FILE_APPEND);

        $this->info("MVC Generator '$name' has been created.");


    }
}
