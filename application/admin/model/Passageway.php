<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 16:53
 */

namespace app\admin\model;


class Passageway extends BaseModel
{
    public function device()
    {
        return $this->belongsTo('Device','device_id','device_id');
    }

    public function category()
    {
        return $this->belongsTo('PassagewayCategory','category_id','id');
    }

    public function getCountTimeAttr()
    {
        return 60;
    }
}