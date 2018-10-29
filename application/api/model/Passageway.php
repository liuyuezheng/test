<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 15:22
 */

namespace app\api\model;


use think\Model;

class Passageway extends Base
{
    public function device()
    {
        return $this->belongsTo('Device','device_id','device_id');
    }
    public function passagewayCategory(){
        return $this->belongsTo('PassagewayCategory','category_id','id');
    }
}