<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/20
 * Time: 上午11:37
 */

namespace App\Module\Huobi\Product;

use App\Module\Huobi\Api;

class Btc extends Api
{

    protected static $_instance;

    const FLAG = 1;

    public function getLastPrice()
    {
        return parent::getProductTraceInfo(self::FLAG)['ticker']['last'];
    }

    public function buyCoins($price, $amount)
    {
        return parent::__buy($price, $amount, self::FLAG);
    }

    public function saleCoins($price, $amount)
    {
        return parent::__sale($price, $amount, self::FLAG);
    }

    public function cancelOrder($orderId)
    {
        return parent::__cancel($orderId, self::FLAG);
    }

    public function queryOrder($orderId)
    {
        return parent::__query($orderId, self::FLAG);
    }

    public function buyCoinsAuto($price)
    {
        // 当前余额
        $money = self::getMoney();

        $amount = floor(($money / $price) * 10000) / 10000;

        $res = parent::__buy($price, $amount, self::FLAG);

        $res['data']['amount'] = $amount;
        $res['data']['price'] = $price;

        return $res;
    }

    public function getAllOrders()
    {
        return parent::__getAllOrdes(self::FLAG);
    }

    public function buyMarket($amount)
    {
        return parent::_buyMarket(self::FLAG, $amount);
    }

    public function sellMarket($amount)
    {
        return parent::_sellMarket(self::FLAG, $amount);
    }
}