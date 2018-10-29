<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 10:12
 */

namespace app\admin\model;


use app\library\ApiException;
use think\Cache;
use think\Exception;
use think\Request;

class UserToken
{
    public function getToken($username,$password)
    {
        //检查该用户是否存在
        $user = ProjectAdmin::where('account_number','=',$username)
            ->where('password','=',md5($password))
            ->where('type','=',0)
            ->find();
        if (!$user)
        {
            throw new ApiException(['msg' =>'账户或密码错误']);
        }
        return $this->grantToken($user);
    }

    private function grantToken($user)
    {
        $cachedValue = $this->prepareCachedValue($user);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        //32个字符
        $randChars = getRandChar(32);
        //用三组加密
        $timestamp = time();
        $key = md5($randChars.$timestamp);

        $value = json_encode($cachedValue);

        $request = cache($key, $value);
        if (!$request) {
            throw new ApiException([
                'msg' => '服务器缓存异常'
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($user){
        $cachedValue['uid'] = $user['id'];
        return $cachedValue;
    }

    public static function generateToken(){
        //32个字符
        $randChars = getRandChar(32);
        //用三组加密
        $timestamp = time();
        return md5($randChars.$timestamp);

    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new ApiException(['msg' => '登录过期']);
        }
        else{
            if (!is_array($vars)) {
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                throw new ApiException(['msg' =>'尝试获取得Token变量并不存在']);
            }
        }
    }

    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }
}