<?php

namespace Mohammedkh21\MvcGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Mohammedkh21\MvcGenerator\Services\MvcGeneratorService;

class MvcGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:MvcGenerator {name} {attributes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make MVC';

    /**
     * Execute the console command.
     */
    public function handle(MvcGeneratorService $mvcGeneratorService)
    {
        $mvcGeneratorService->name = $this->argument('name');
        $mvcGeneratorService->attributes = explode(',',$this->argument('attributes'));

        // Generate Model
        $mvcGeneratorService->model();

        // Generate Migration
        $mvcGeneratorService->migration();

        // Generate Seeder
        $mvcGeneratorService->seeder();

        // Generate Resource Controller
        $mvcGeneratorService->controller();

        // Generate Resource Route
        $mvcGeneratorService->route();

        $this->info("MVC Generator '$mvcGeneratorService->name' has been created.");
    }
}
