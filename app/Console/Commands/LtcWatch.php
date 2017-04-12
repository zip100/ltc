<?php

namespace App\Console\Commands;

use App\Ltc;
use Illuminate\Console\Command;

class LtcWatch extends Command
{

    private $accessKey;

    private $secretKey;

    private $count;

    private $last = 0;

    private $buy_price = 0;

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

        $this->accessKey = config('huobi.access_key');
        $this->secretKey = config('huobi.secret_key');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        var_export($this->getAccountInfo());

        while (1) {
            $price = $this->getLtcPrice();

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

            // 跌了3毛钱开始买
            if ($this->buy_price == 0 && $this->count < -30) {
                $this->buy($ltc->price);
            }

        }
    }

    /**
     * 最后交易价格
     * @return array
     */
    private function getLtcPrice()
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


    private function buy($price)
    {
        $this->buy_price = $price;
        \Log::info("Buy " . $price);

        if($price > 5700){
            return;
        }

        $tParams = $extra = array();
        $tParams['method'] = 'buy';
        $tParams['coin_type'] = '2';
        $tParams['price'] = $price / 100;
        $tParams['amount'] = '1';
        $tResult = $this->send2api($tParams, $extra);

        return $tResult;
    }

    private function sale($price, $amount)
    {
        \Log::info("Sale " . $price);
        $this->buy_price = 0;


        $tParams = $extra = array();
        $tParams['method'] = 'sell';
        $tParams['coin_type'] = '2';
        $tParams['price'] = $price / 100;
        $tParams['amount'] = $amount;
        $tResult = $this->send2api($tParams, $extra);
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

    private function getAccountInfo()
    {
        $tParams = $extra = array();
        $tParams['method'] = 'get_account_info';
        // 不参与签名样例
        // $extra['test'] = 'test';
        $tResult = $this->send2api($tParams, $extra);
        return $tResult;
    }
}
