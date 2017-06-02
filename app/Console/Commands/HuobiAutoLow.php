<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HuobiAutoLow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'huobi:auto_low {var}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $var = $this->argument('var');

        if (!in_array($var, ['0', '1'])) {
            throw new \Exception('invalid var');
        }

        \Cache::forever('auto_low', $var);

        echo printf('SET AUTO LOW SUCCESS'), PHP_EOL;
    }
}
