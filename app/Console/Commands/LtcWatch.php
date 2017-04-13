<?php

namespace App\Console\Commands;

use App\Jobs\OrderQuery;
use App\Ltc;
use App\Model\Order;
use App\Module\Huobi;
use Illuminate\Console\Command;

class LtcWatch extends Command
{

    private $count;

    private $last = 0;

    private $buy_price = 0;

    private $processer;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ltc:watch';

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

        $this->processer = new Huobi();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        var_export($this->processer->getAccountInfo());

        while (1) {
            $price = $this->processer->getLtcPrice();

            if (!$price) {
                break;
            }
            $this->process($price);
        }
    }


    public function test()
    {
        while (1) {
            $this->process(rand(5500, 5600));
        }
    }

    private function process($price)
    {
        if ($price != $this->last) {
            $ltc = new \App\Model\Ltc();
            $ltc->price = $price;
            $ltc->amount = $ltc->price - $this->last;
            $ltc->save();

            if ($ltc->amount > 0) {
                $this->count = 0;
            } else {
                $this->count += $ltc->amount;
            }

            echo sprintf('%s %d %d', $price, $ltc->amount, $this->count), PHP_EOL;
            $this->last = $ltc->price;

            // 跌了3.5毛钱开始买
            if ($this->buy_price == 0 && $this->count < -35) {

                $amount = 1;

                $res = $this->processer->buy($ltc->price, $amount);

                $order = new Order();
                $order->order_id = $res['id'];
                $order->amount = $amount;
                $order->price = $ltc->price;
                $order->status = 0;
                $order->save();

                echo printf("Buy Price:%d Count:%d OrderId:%d", $ltc->price, $amount, $order->id), PHP_EOL;

                $job = new OrderQuery($order->id);
                dispatch($job);
            }

        }
    }

}
