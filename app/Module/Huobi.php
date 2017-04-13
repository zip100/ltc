<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/12
 * Time: 下午3:12
 */

namespace App\Module;

class Huobi
{
    private $accessKey;

    private $secretKey;

    function __construct()
    {
        $this->accessKey = config('huobi.access_key');
        $this->secretKey = config('huobi.secret_key');
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
        // 不参与签名样例
        // $extra['test'] = 'test';
        $tResult = $this->send2api($tParams, $extra);
        return $tResult;
    }

    public function queryOrder($orderId)
    {
        $tParams = $extra = array();
        $tParams['method'] = 'order_info';

        $tParams['coin_type'] = '2';
        $tParams['id'] = $orderId;

        // 不参与签名样例
        // $extra['test'] = 'test';
        $tResult = $this->send2api($tParams, $extra);
        return $tResult;

    }

    public function sale($price, $amount)
    {
        \Log::info("Sale " . $price);
        $this->buy_price = 0;


        $tParams = $extra = array();
        $tParams['method'] = 'sell';
        $tParams['coin_type'] = '2';
        $tParams['price'] = $price / 100;
        $tParams['amount'] = $amount;
        $tResult = $this->send2api($tParams, $extra);

        echo printf("Sell Price:%d Count:%d", $price, $amount), PHP_EOL;
    }


    /**
     * 最后交易价格
     * @return array
     */
    public function getLtcPrice()
    {
        try {
            $url = 'http://api.huobi.com/staticmarket/ticker_ltc_json.js';
            $json = file_get_contents($url, true);
            if (!$json) {
                throw new \Exception('Ltc Json Null');
            }
        } catch (\Exception $e) {
            \Log::error('QueryLtcInfoException ' . $e->getMessage());
            return $this->getLtcPrice();
        }
        return json_decode($json, true)['ticker']['last'] * 100;
    }

    public function buy($price, $amount)
    {
        \Log::info("Buy " . $price);
        $this->buy_price = 0;


        $tParams = $extra = array();
        $tParams['method'] = 'buy';
        $tParams['coin_type'] = '2';
        $tParams['price'] = $price / 100;
        $tParams['amount'] = $amount;
        return $this->send2api($tParams, $extra);
    }

    public function cancelOrder($id){

        \Log::info("Cancel " . $id);
        $this->buy_price = 0;


        $tParams = $extra = array();
        $tParams['method'] = 'cancel_order';
        $tParams['coin_type'] = '2';
        $tParams['id'] = $id;
        return $this->send2api($tParams, $extra);
    }
}