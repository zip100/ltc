<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    const STATUS_WAIT = 0;
    const STATUS_FINISH = 1;

    public function fire()
    {
        $this->status = self::STATUS_FINISH;
        $this->save();
    }
}
