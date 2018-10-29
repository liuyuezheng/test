<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 18:19
 */

namespace app\api\model;


use think\Model;

    class Projects extends Base
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