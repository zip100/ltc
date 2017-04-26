<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/21
 * Time: 下午1:06
 */
namespace App\Module;
class Yunxinshi
{
    private $uid;
    private $pwd;

    function __construct($config)
    {
        $this->uid = $config['uid'];
        $this->pwd = $config['pwd'];
    }

    public function send($mobile, $templadeId, array $content)
    {
        $urlTemplate = 'http://api.sms.cn/sms/?ac=send&uid=%s&pwd=%s&template=%s&mobile=%s&content=%s';
        $url = sprintf($urlTemplate,
            $this->uid,
            $this->pwd,
            $templadeId,
            $mobile,
            json_encode($content)
        );
        try {
            $res = file_get_contents($url);
            if (!$res) {
                throw new \Exception('[Sms][Send][Exception] return null');
            }
            $res = json_decode(iconv('GB2312', 'UTF-8', $res), true);
            if (!isset($res['stat']) || $res['stat'] != '200') {
                throw new \Exception('[Sms][Send][Exception] ' . json_encode($res));
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
        return true;
    }
}