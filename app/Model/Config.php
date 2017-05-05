<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    const AUTO_BUY_LTC_WHEN_NOTICE = 1;
    const ENABLED_SMS = 2;

    const TYPE_CHECKBOX = 1;
}
