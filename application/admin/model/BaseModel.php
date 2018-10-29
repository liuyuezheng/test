<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 9:48
 */

namespace app\admin\model;


use think\Model;

class BaseModel extends Model
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