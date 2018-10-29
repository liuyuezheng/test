<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 11:13
 */

namespace app\api\model;


use think\Model;

class Project_admin extends Base
{
    public function setPasswordAttr($password){
        return md5($password);
    }
    public function device()
    {
        return $this->belongsToMany('Device','project_admin_device','device_id','project_admin_id');
    }
}