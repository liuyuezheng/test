<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 9:59
 */

namespace app\api\model;


use think\Model;

class Base extends Model
{
    protected function prefixImgUrl($value)
    {
        $finalUrl = $value;
        if ($value){
            $finalUrl = config('setting.img_prefix').$value;
        }
        return $finalUrl;
    }

}