<?php

namespace App\Jobs;

use App\Model\Order;
use App\Module\Huobi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrderQuery implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderId)
    {
        $this->order = Order::findOrFail($orderId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $hanndle = new Huobi();

        if ($this->attempts() >= 10) {
            \Log::info(sprintf("买入超时,取消订单 %s", $this->order->id));
            $hanndle->cancelOrder($this->order->order_id);
            sleep(1);
        }

        $response = $hanndle->queryOrder($this->order->order_id);

        if (!isset($response['status'])) {
            $this->release(3);
        }

        if ($response['status'] != $this->order->status) {
            $this->order->status = $response['status'];
            $this->order->save();
        }

        if ($this->order->status == 2) {
            $price = $this->order->price + 50;
            $hanndle->sale($price, ($this->order->amount - $this->order->amount * 0.002));
        }

        if (in_array($this->order->status, [0, 1, 7])) {
            $this->release(3);
        }
    }
}
