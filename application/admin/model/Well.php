<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/10
 * Time: 21:03
 */

namespace app\admin\model;


class Well extends BaseModel
{
    public function project()
    {
        return $this->belongsTo('Projects','project_id','id');
    }
}