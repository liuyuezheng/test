<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 10:15
 */

namespace app\api\controller;


use app\api\model\Category;
use app\api\model\Device;
use app\api\model\Project_admin;
use app\api\model\Project_admin_device;
use app\api\model\Project_data;
use think\Db;
use think\Request;

class Datum extends Base
{
    //资料查询-分项监测

    public function itemized($page=1,$size=5){
        $uid=\app\api\service\Token::getCurrentUid();
//        $uid=Request::instance()->get('aid',0);
//        $uid=3;
        $info=Project_admin::where('id',$uid)->find();
        $cid=Request::instance()->get('category_id',0);//区域id
        if($info['type']==1){
            if($cid){
                $device=Project_data::alias('p')
                    ->join('device d','p.device_id=d.device_id','left')
                    ->join('category c','c.id=p.category_id')
                    ->where('d.project_id',$info['p_id'])
                    ->where('p.category_id',$cid)
                    ->field('p.*,c.name')
                    ->paginate($size,false,['page'=>$page])->toArray();
            }else{
                $device=Project_data::alias('p')
                    ->join('device d','p.device_id=d.device_id','left')
                    ->join('category c','c.id=p.category_id')
                    ->where('d.project_id',$info['p_id'])
                    ->field('p.*,c.name')
                    ->paginate($size,false,['page'=>$page])->toArray();
            }
        }else{
            if($cid){
                $device=Project_data::alias('p')
                    ->join('project_admin_device d','p.device_id=d.device_id')
                    ->join('category c','c.id=p.category_id')
                    ->where('d.project_admin_id',$uid)
                    ->where('p.category_id',$cid)
                    ->field('p.*,c.name')
                    ->paginate($size,false,['page'=>$page])->toArray();
            }else{
                $device=Project_data::alias('p')
                    ->join('project_admin_device d','p.device_id=d.device_id')
                    ->join('category c','c.id=p.category_id')
                    ->where('d.project_admin_id',$uid)
                    ->field('p.*,c.name')
                    ->paginate($size,false,['page'=>$page])->toArray();
            }
        }
        $data['total']=$device['total'];
        $data['per_page']=$device['per_page'];
        $data['current_page']=$device['current_page'];
        $data['last_page']=$device['last_page'];
        if(!$device['data']){
            $data['data']=[];
        }
        foreach ($device['data'] as $k=>$v){
            $data['data'][$k]['id']=$v['id'];
            $data['data'][$k]['device_id']=$v['device_id'];
            $data['data'][$k]['category_id']=$v['category_id'];
            $data['data'][$k]['title']=$v['title'];
            $data['data'][$k]['cover']=$v['cover'];
            $data['data'][$k]['content']=$v['content'];
            $data['data'][$k]['create_time']=$v['create_time'];
            $data['data'][$k]['category_name']=$v['name'];
        }
        return $this->success('请求成功','',$data);

    }
    //分项监测所属区域
    public function  device(){
      $data=Category::select();
//        $data=Project_data::select();
      return $this->success('请求成功','',$data);
    }
    //分项监测-删除
    public function delpro(){
        $pid=Request::instance()->get('id',0);//资料id
        $uid=\app\api\service\Token::getCurrentUid();
        $info=Project_admin::where('id',$uid)->find();
        if($info['type']==1){
        $info=Project_data::where('id',$pid)->delete();
            if($info==1){
                return $this->success('删除成功','');
            }
        }else{
            return $this->error('无权限','');
        }
    }


}