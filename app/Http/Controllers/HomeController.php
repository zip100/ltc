<?php

namespace App\Http\Controllers;

use App\Jobs\OrderQuery;
use App\Model\Huobi;
use App\Model\Order;
use App\Module\Huobi\Product\Btc;
use App\Module\Huobi\Product\Ltc;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getData()
    {
        return Datatables::of(Huobi::query()->where('id', '>', 1)->orderBy('id', 'desc'))->make(true);
    }

    public function getOrderData()
    {
        return Datatables::of(Order::query())->make(true);
    }

    public function getBuyLtc()
    {
        $account = Btc::getInstance()->getAccountInfo();

        return view('buy', ['account' => $account]);
    }

    public function postBuyBtc(Request $request)
    {
        $this->validate($request, [
            'buy_price' => 'required'
        ]);

        $buyPrice = $request->get('buy_price');
        $sellPrice = $request->get('sell_price', 0);


        if ($sellPrice > 0 && $sellPrice - $buyPrice < 0.5) {
            return redirect()->back()->withErrors('买入卖出价格区间不能小于 0.5');
        }

        $res = Ltc::getInstance()->buyCoinsAuto($buyPrice);

        if (isset($res['result']) && $res['result'] == 'success') {
            $order = Order::forceCreate([
                'type' => Ltc::FLAG,
                'buy_price' => $res['data']['price'],
                'buy_amount' => $res['data']['amount'],
                'buy_money' => $res['data']['money'],
                'buy_id' => $res['id'],
                'sell_price' => $sellPrice,
                'sell_amount' => 0,
                'sell_money' => 0,
                'sell_id' => 0,
                'sell_status' => 0,
                'buy_status' => 0,
            ]);

            $job = new OrderQuery($order->id);
            dispatch($job);

            return "Buy Success";
        } else {
            return redirect()->back()->withErrors($res['message']);
        }
    }

    public function getOrders()
    {
        return view('orders');
    }

    public function getOrderInfo($oid)
    {
        $order = Order::findOrFail($oid)->toArray();
        return view('order_info', ['info' => $order]);
    }
}
