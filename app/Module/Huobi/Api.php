<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/20
 * Time: 上午11:29
 */
namespace App\Module\Huobi;

abstract class Api
{

    private $accessKey, $secretKey, $ticker;

    const CONIN_BTC = 1;
    const CONIN_LTC = 2;


    function __construct()
    {
        $this->accessKey = config('huobi.access_key');
        $this->secretKey = config('huobi.secret_key');
        $this->ticker = config('huobi.api.ticker');
    }

    private function httpRequest($pUrl, $pData)
    {
        $tCh = curl_init();
        if ($pData) {
            is_array($pData) && $pData = http_build_query($pData);
            curl_setopt($tCh, CURLOPT_POST, true);
            curl_setopt($tCh, CURLOPT_POSTFIELDS, $pData);
        }
        curl_setopt($tCh, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
        curl_setopt($tCh, CURLOPT_URL, $pUrl);
        curl_setopt($tCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($tCh, CURLOPT_SSL_VERIFYPEER, false);
        $tResult = curl_exec($tCh);
        curl_close($tCh);
        $tmp = json_decode($tResult, 1);
        if ($tmp) {
            $tResult = $tmp;
        }
        return $tResult;
    }

    private function send2api($pParams, $extra = array())
    {
        $pParams['access_key'] = $this->accessKey;;
        $pParams['created'] = time();
        $pParams['sign'] = $this->createSign($pParams);
        if ($extra) {
            $pParams = array_merge($pParams, $extra);
        }
        $tResult = $this->httpRequest('https://api.huobi.com/apiv3', $pParams);

        \Log::info(sprintf("Request:%s", json_encode($pParams)));
        \Log::info(sprintf("Response:%s", json_encode($tResult)));

        return $tResult;
    }

    private function createSign($pParams = array())
    {
        $pParams['secret_key'] = $this->secretKey;
        ksort($pParams);
        $tPreSign = http_build_query($pParams);
        $tSign = md5($tPreSign);
        return strtolower($tSign);
    }

    public function getAccountInfo()
    {
        $tParams = $extra = array();
        $tParams['method'] = 'get_account_info';
        $tResult = $this->send2api($tParams, $extra);
        return $tResult;
    }

    protected function __query($orderId, $type)
    {
        $tParams = $extra = array();
        $tParams['method'] = 'order_info';
        $tParams['coin_type'] = $type;
        $tParams['id'] = $orderId;
        $tResult = $this->send2api($tParams, $extra);
        return $tResult;

    }

    protected function __sale($price, $amount, $type)
    {
        \Log::info("Sale " . $price);
        $this->buy_price = 0;
        $tParams = $extra = array();
        $tParams['method'] = 'sell';
        $tParams['coin_type'] = $type;
        $tParams['price'] = round($price, 2);
        $tParams['amount'] = $amount;
        $tResult = $this->send2api($tParams, $extra);

        echo printf("Sell Price:%d Count:%d", $price, $amount), PHP_EOL;

        return $tResult;
    }

    protected function __buy($price, $amount, $type)
    {
        \Log::info("Buy " . $price);
        $tParams = $extra = array();
        $tParams['method'] = 'buy';
        $tParams['coin_type'] = $type;
        $tParams['price'] = $price;
        $tParams['amount'] = $amount;

        $res = $this->send2api($tParams, $extra);
        $res['data']['amount'] = parseMoney($amount - $amount * 0.002);
        $res['data']['price'] = $price;
        $res['data']['money'] = $amount * $price;

        return $res;
    }

    protected function __cancel($id, $type)
    {

        \Log::info("Cancel " . $id);
        $tParams = $extra = array();
        $tParams['method'] = 'cancel_order';
        $tParams['coin_type'] = $type;
        $tParams['id'] = $id;
        return $this->send2api($tParams, $extra);
    }

    /**
     * 最后Btc交易价格
     * @return array
     */
    public function getProductTraceInfo($type)
    {
        try {
            $url = $type == self::CONIN_BTC ? $this->ticker['btc'] : $this->ticker['ltc'];
            $json = file_get_contents($url, true);
            if (!$json) {
                throw new \Exception('Ltc Json Null');
            }
        } catch (\Exception $e) {
            \Log::error('[ProductTraceInfo][QueryException] ' . $e->getMessage());
            return $this->getProductTraceInfo($type);
        }
        return json_decode($json, true);
    }


    public function getMoney()
    {
        $info = $this->getAccountInfo();
        return isset($info['available_cny_display']) ? $info['available_cny_display'] : -1;
    }

    public static function getInstance()
    {
        if (!static::$_instance) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }

    public function __getAllOrdes($type)
    {
        $tParams = $extra = array();
        $tParams['method'] = 'get_orders';
        $tParams['coin_type'] = $type;
        return $this->send2api($tParams, $extra);
    }

    public static function sendSms($mobile, array $content)
    {
        return \Sms::send($mobile, 400046, $content);
    }

    protected function _buyMarket($type, $amount)
    {
        $tParams = $extra = array();
        $tParams['method'] = 'buy_market';
        $tParams['coin_type'] = $type;
        $tParams['amount'] = $amount;
        return $this->send2api($tParams, $extra);
    }

    protected function _sellMarket($type, $amount)
    {
        $tParams = $extra = array();
        $tParams['method'] = 'sell_market';
        $tParams['coin_type'] = $type;
        $tParams['amount'] = $amount;
        return $this->send2api($tParams, $extra);
    }

    abstract public function getLastPrice();

    abstract public function buyCoins($price, $amount);

    abstract public function buyCoinsAuto($price);

    abstract public function saleCoins($price, $amount);

    abstract public function cancelOrder($orderId);

    abstract public function queryOrder($orderId);

    abstract public function getAllOrders();

    abstract public function buyMarket($amount);
    
    abstract public function sellMarket($amount);
}