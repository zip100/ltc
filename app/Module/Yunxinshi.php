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

    private $statMap = [
        100 => '发送成功',
        101 => '验证失败',
        102 => '短信不足',
        103 => '操作失败',
        104 => '非法字符',
        105 => '内容过多',
        106 => '号码过多',
        107 => '频率过快',
        108 => '号码内容空',
        109 => '账号冻结',
        112 => '号码错误',
        113 => '定时出错',
        116 => '禁止接口发送',
        117 => '绑定IP不正确',
        161 => '未添加短信模板',
        162 => '模板格式不正确',
        163 => '模板ID不正确',
        164 => '全文模板不匹配',
    ];

    function __construct($config)
    {
        $this->uid = $config['uid'];
        $this->pwd = $config['pwd'];
    }

    public function send($mobile, $templadeId, array $content)
    {
        if (!allowSms()) {
            \Log::info(sprintf('[Sms][Send][Disabled][Success][Request:%s]', json_encode($content)));
            return true;
        }

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
            if (!isset($res['stat']) || $res['stat'] != '100') {
                throw new \Exception('[Sms][Send][Exception] ' . json_encode($res));
            }
        } catch
        (\Exception $e) {
            \Log::info($e->getMessage());
            return false;
        }
        \Log::info(sprintf('[Sms][Send][Success][Request:%s][Response:%s]', json_encode($content), json_encode($res)));
        return true;
    }
}