<?php

namespace App\Console\Commands;

use App\Jobs\OrderQuery;
use App\Model\Order;
use App\Module\Huobi\Product\Btc;
use App\Module\Huobi\Product\Ltc;
use Illuminate\Console\Command;

class OrderSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:sync';

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
        while (1) {
            $this->btc();
            $this->ltc();

            sleep(5);
        }
    }

    private function btc()
    {
        $btcOrders = Btc::getInstance()->getAllOrders();

        $typeMap = ['1' => 'buy_id', '2' => 'sell_id'];

        if (is_array($btcOrders) && count($btcOrders) > 0) {
            foreach ($btcOrders as $bo) {
                $exists = Order::where($typeMap[$bo['type']], $bo['id'])->first();
                if (!$exists) {
                    $order = ['type' => Btc::FLAG];
                    if ($bo['type'] == '1') {
                        $order['buy_price'] = $bo['order_price'];
                        $order['buy_amount'] = $bo['order_amount'];
                        $order['buy_money'] = parseMoney($order['buy_price'] * $order['buy_amount']);
                        $order['buy_id'] = $bo['id'];
                    } else {
                        $order['sell_price'] = $bo['order_price'];
                        $order['sell_amount'] = $bo['order_amount'];
                        $order['sell_money'] = parseMoney($order['sell_price'] * $order['sell_amount']);
                        $order['sell_id'] = $bo['id'];
                    }

                    $order = Order::forceCreate($order);
                    $job = new OrderQuery($order->id);
                    dispatch($job);
                }
            }
        }

    }

    private function ltc()
    {
        $btcOrders = Ltc::getInstance()->getAllOrders();

        $typeMap = ['1' => 'buy_id', '2' => 'sell_id'];

        if (is_array($btcOrders) && count($btcOrders) > 0) {
            foreach ($btcOrders as $bo) {
                $exists = Order::where($typeMap[$bo['type']], $bo['id'])->first();
                if (!$exists) {
                    $order = ['type' => Ltc::FLAG];
                    if ($bo['type'] == '1') {
                        $order['buy_price'] = $bo['order_price'];
                        $order['buy_amount'] = $bo['order_amount'];
                        $order['buy_money'] = parseMoney($order['buy_price'] * $order['buy_amount']);
                        $order['buy_id'] = $bo['id'];
                    } else {
                        $order['sell_price'] = $bo['order_price'];
                        $order['sell_amount'] = $bo['order_amount'];
                        $order['sell_money'] = parseMoney($order['sell_price'] * $order['sell_amount']);
                        $order['sell_id'] = $bo['id'];
                    }

                    $order = Order::forceCreate($order);
                    $job = new OrderQuery($order->id);
                    dispatch($job);
                }
            }
        }

    }
}
