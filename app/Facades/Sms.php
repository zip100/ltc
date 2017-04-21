<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/21
 * Time: 下午12:57
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Yunxinshi';
    }
}