<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/9/3
 * Time: 23:07
 */

namespace app\admin\controller;


class Api extends BaseController
{
    public function send()
    {
        $fp = fsoctopen('202.117.120.241', 2012);
        fwrite($fp, chr(bindec('00100000')));
    }
}