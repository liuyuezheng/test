<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 14:34
 */

namespace app\api\model;


use think\Model;

class Project_inspect_log extends Base
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