<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 10:57
 */

namespace app\api\model;


use think\Model;

class Project_data extends Base
{
    public function category()
    {
        return $this->hasMany('Category','id','category_id');
    }
    public function getCoverAttr($value)
    {
        return $this->prefixImgUrl($value);
    }
    public function device()
    {
        return $this->hasMany('Device','device_id','device_id');
    }
}