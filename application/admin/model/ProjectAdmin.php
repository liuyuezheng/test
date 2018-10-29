<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 17:34
 */

namespace app\admin\model;


class ProjectAdmin extends BaseModel
{

    public function setPasswordAttr($value)
    {
        return md5($value);
    }

    public function project()
    {
        return $this->belongsTo('Projects','p_id','id');
    }

    public function device()
    {
        return $this->belongsToMany('Device','ProjectAdminDevice','device_id','project_admin_id');
    }
}