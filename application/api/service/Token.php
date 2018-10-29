<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 11:29
 */

namespace app\api\service;

//use app\api\lib\exception\ForbiddenException;
//use app\api\lib\exception\TokenException;
use app\library\ApiException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken(){
        //32个字符
        $randChars = getRandChar(32);
        //用三组加密
        $timestamp = $_SERVER['REQUEST_TIME'];
        //盐
        $salt = config('secure.token_salt');

        return md5($randChars.$timestamp.$salt);

    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new ApiException([
                'msg'=> '登录已过期'
            ]);
        }
        else{
            if (!is_array($vars)) {
                $vars = json_decode($vars,true);
            }
            if (array_key_exists($key,$vars)){
                return $vars[$key];
            }
            else{
                throw new Exception('尝试获取得Token变量并不存在');
            }
        }
    }

    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    //需要用户权限
//    public static function needPrimaryScope()
//    {
//        $scope = self::getCurrentTokenVar('scope');
//        if ($scope) {
//            if ($scope >= ScopeEnum::User) {
//                return true;
//            }
//            else{
//                throw new ForbiddenException();
//            }
//        }
//        else{
//            throw new TokenException();
//        }
//    }

//    //只要用户才能访问
//    public static function needExclusiveScope()
//    {
//        $scope = self::getCurrentTokenVar('scope');
//        if ($scope) {
//            if ($scope == ScopeEnum::User) {
//                return true;
//            }
//            else{
//                throw new ForbiddenException();
//            }
//        }
//        else{
//            throw new TokenException();
//        }
//    }

    public static function isValidOperate($checkedUID)
    {
        if (!$checkedUID) {
            throw new Exception('检测UID不能是NULL');
        }
        $currentOperateUID = self::getCurrentUid();
        if ($currentOperateUID == $checkedUID) {
            return true;
        }else{
            return false;
        }

    }



}