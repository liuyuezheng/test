<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 9:58
 */

namespace app\admin\model;


class Device extends BaseModel
{
    public function project()
    {
        return $this->belongsTo('Projects', 'project_id', 'id');
    }

    public function admin()
    {
        return $this->belongsToMany('ProjectAdmin', 'ProjectAdminDevice', 'project_admin_id', 'device_id');
    }

}