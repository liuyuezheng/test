<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 10:15
 */

namespace app\admin\controller;


use app\admin\model\UserToken;
use think\Controller;

class Token extends Controller
{
    public function getToken()
    {
        $username = input('param.username/s','');
        $password = input('param.password/s','');
        $ut = new UserToken();
        $token = $ut->getToken($username,$password);

        $data['token'] = $token;
        return $this->success('登录成功','',$data);
    }
}