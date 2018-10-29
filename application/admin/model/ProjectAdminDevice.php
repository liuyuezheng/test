<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/24
 * Time: 10:10
 */

namespace app\admin\model;


class ProjectAdminDevice extends BaseModel
{
    public function admin()
    {
        return $this->belongsTo('ProjectAdmin','project_admin_id','id');
    }
}