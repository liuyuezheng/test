<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/28
 * Time: 16:21
 */

namespace app\api\model;


use think\Model;

class DeviceLog extends Base
{
    //base64上传图片
    public function setImgAttr($value)
    {
        return json($value);
    }

    public function getImgAttr($value)
    {
        $array = [];
        if ($value)
        {
            $array = json_decode($value,true);
            foreach ($array as &$v)
            {
                $v = $this->prefixImgUrl($v);
            }
        }
        return $array;
    }
}