<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/30
 * Time: 17:07
 */

namespace app\admin\model;


class ProjectInspectLog extends BaseModel
{
    public function setImgAttr($value)
    {
        return json_encode($value);
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