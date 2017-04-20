<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/20
 * Time: 上午11:38
 */

namespace App\Module\Huobi\Product;

use App\Module\Huobi\Api;

class Ltc extends Api
{

    protected static $_instance;

    const FLAG = 2;

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
}