<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 11:31
 */

namespace app\api\service;


//use app\api\lib\exception\TokenException;
use app\api\model\Project_admin;
use think\Db;
use think\Exception;

class UserToken extends Token
{
    public function get($name,$password)
    {
//        VerificationCode::checkCode($mobile,$code);

        //检查该用户是否存在
        $user = Project_admin::where('account_number','=',$name)
            ->where('password','=',md5($password))
            ->where('type','in',[1,2])
            ->find();
        if (!$user)
        {
            throw new Exception('账户或密码错误');
        }
//        return $user;
        return $this->grantToken($user);
    }

    private function grantToken($user)
    {
        $cachedValue = $this->prepareCachedValue($user);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
//        $expire_in = config('setting.token_expire_in');
        $request = cache($key, $value);
        if (!$request) {
            throw new Exception([
                'msg' => '服务器缓存异常',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($user){
        $cachedValue['uid'] = $user['id'];
        return $cachedValue;
    }
}