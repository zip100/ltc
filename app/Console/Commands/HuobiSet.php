<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class HuobiSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'huobi:set {type} {price}';

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
        $price = $this->argument('price');
        $type = $this->argument('type');

        if (!in_array($type, ['btc', 'ltc'])) {
            throw new \Exception('type not allow');
        }

        \Cache::forever(sprintf('%s_price', $type), $price);

        echo sprintf('SET SUCCESS!'), PHP_EOL;
    }
}
