<?php

namespace Mohammedkh21\MvcGenerator;

use Illuminate\Console\Command;

class MvcGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:MvcGenerator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        info('MvcGenerator');
    }
}
