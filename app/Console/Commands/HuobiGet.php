<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HuobiGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'huobi:get {type}';

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

        $type = $this->argument('type');

        if (!in_array($type, ['btc', 'ltc'])) {
            throw new \Exception('type not allow');
        }

        $key = sprintf('%s_price', $type);

        $var = \Cache::get($key);

        echo sprintf('%s : %s', $key, $var), PHP_EOL;


    }
}
