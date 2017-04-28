<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2017/4/28
 * Time: 下午4:32
 */
function parseStatus($status)
{
    switch ($status) {
        case '0':
            return '未成交';
            break;
        case '1':
            return '部分成交';
            break;
        case '2':
            return '已完成';
            break;
        case '3':
            return '已取消';
            break;
        case '4':
            return '废弃';
            break;
        case '5':
            return '异常';
            break;
        case '6':
            return '部分成交已取消';
            break;
        case '7':
            return '队列中';
            break;
    }
}