<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 14:21
 */

namespace app\api\model;


use think\Model;

class Device extends Base
{

    public function getStatusAttr($value,$data)
    {
        if ($data['alarm_type'] == 1)
        {
            return 0;
        }else{
            return $value;
        }
    }
    public function passageway()
    {
        return $this->belongsTo('Passageway','device_id','device_id');
    }


}