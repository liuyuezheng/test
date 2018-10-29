<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 10:03
 */

namespace app\admin\model;


class Projects extends BaseModel
{
    public function device()
    {
        return $this->hasMany('Device','project_id','id');
    }

    public function getLogoAttr($value)
    {
        return $this->prefixImgUrl($value);
    }
}