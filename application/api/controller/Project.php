<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 10:21
 */

namespace app\api\controller;


use app\admin\model\DeviceLog;
use app\admin\model\Projects;
use app\api\model\Device;
use app\api\model\Device_data;
use app\api\model\Passageway;
use app\api\model\PassagewayCategory;
use app\api\model\Project_admin;
use app\api\model\Project_admin_device;
use app\api\model\Read_device;
use think\Controller;
use think\Db;
use think\Request;

class Project  extends Base
{
    //项目详情
     public function promessage(){
         $uid=\app\api\service\Token::getCurrentUid();
         $res=Project_admin::where('id',$uid)->find();
//         if($res['type']==1){
             $info = Projects::where('id',$res['p_id'])
                 ->order('build_start_time desc')
                 ->relation(['device'])->select();
             $data = [];
             foreach ($info as $value)
             {
                 $count = count($value['device']);
                 $data[] = [
                     'id' => $value['id'],
                     'project_name' => $value['project_name'],
                     'device_count' => $count,
                     'build_start_time' => $value['build_start_time'],
                     'longitude' => $value['longitude'],
                     'latitude' => $value['latitude'],
                     'province' => $value['province'],
                 ];
             }
         return $this->success('请求成功','',$data);
//            ;
//         }else{
//             $where=[];
//             if($device_id!=""){
//                  $where['d.device_id']= ['=',$device_id];
//             }
//             $data=Device::alias('d')->join('projects p','p.id=d.project_id')
//                 ->join('project_admin_device a','a.device_id=d.device_id')
//                 ->where('a.project_admin_id',$uid)
//                 ->where($where)
//                 ->order('d.install_last_time desc')
//                 ->field('d.project_id,p.id,p.project_name,p.build_start_time,p.longitude,p.latitude,p.province')
//                 ->find();
//             $count=Device::where('project_id',$data['project_id'])->select();
//             $data['device_count']=count($count);
////             Project_admin_device::alias('a')->join('device')
//         }

     }
     //项目概况-站点列表
     public function project(){
         $uid=\app\api\service\Token::getCurrentUid();
//         $uid=3;
         $device=new Device();
         $res=Project_admin::where('id',$uid)->find();
         if($res['type']==1){

             $data=$device->where('project_id',$res['p_id'])
                 ->order('install_last_time desc')
                 ->field('device_name,device_id,install_last_time,maintain_last_worker,longitude,latitude,status,alarm_type')
                 ->select();
         }else{
             $data=$device->join('project_admin_device p','p.device_id=device.device_id')
               ->where('p.project_admin_id',$uid)
               ->order('device.install_last_time desc')
               ->field('device.device_name,device.device_id,device.install_last_time,device.maintain_last_worker,device.longitude,device.latitude,device.status,device.alarm_type')
               ->select();
         }
         return $this->success('请求成功','',$data);
     }
     //项目概况-报警列表
    public function police(){
        $device=new Device();
        $uid=\app\api\service\Token::getCurrentUid();
        $res=Project_admin::where('id',$uid)->find();
        if($res['type']==1){
            $data=$device->where('project_id',$res['p_id'])
                ->where('status','in',[1,2])
                ->where('alarm_type',0)
                ->order('install_last_time desc')
                ->field('device_name,device_id,longitude,latitude,status,alarm_type')->select();
        }else{
            $data=$device->join('project_admin_device p','p.device_id=device.device_id')
                ->where('p.project_admin_id',$uid)
                ->where('device.status','in',[1,2])
                ->where('device.alarm_type',0)
                ->order('device.install_last_time desc')
                ->field('device.device_name,device.device_id,device.longitude,device.latitude,device.status,device.alarm_type')
                ->select();
        }
        return $this->success('请求成功','',$data);
    }
    //项目概况-通道
    public function details(){
         $did=Request::instance()->get('device_id',0);//站点id
         $pass=Passageway::with(['passagewayCategory'])->where('device_id',$did)->select();
         $data=[];
         foreach ($pass as $k=>$v){
             $data[$k]['id']=$v['id'];
             $data[$k]['device_id']=$v['device_id'];
             $data[$k]['name']=$v['name'];
             $data[$k]['change_range_max']=$v['change_range_max'];
             $data[$k]['change_range_min']=$v['change_range_min'];
             $data[$k]['max_range']=$v['max_range'];
             $data[$k]['min_range']=$v['min_range'];
             $data[$k]['count_time']=$v['count_time'];
             $data[$k]['value']=$v['value'];
             $data[$k]['change_value']=$v['change_value'];
             $data[$k]['status']=$v['status'];
             $data[$k]['a']=$v['a'];
             $data[$k]['b']=$v['b'];
             $data[$k]['switch_alarm']=$v['switch_alarm'];
             $data[$k]['type']=$v['passageway_category']['type'];
             $data[$k]['category_name']=$v['passageway_category']['name'];
         }
         return $this->success('请求成功','',$data);
    }
    //日志
    public function log(){
        $uid=\app\api\service\Token::getCurrentUid();
        $did=Request::instance()->get('device_id');//站点id
        $data=DeviceLog::join('project_admin','project_admin.id=device_log.project_id')
            ->where('device_log.project_id',$uid)
            ->where('device_log.device_id',$did)
            ->order('time desc')
            ->field('name,device_log.*')
            ->select();
        return $this->success('请求成功','',$data);
    }
    //视频url
    public function video(){
        $uid=\app\api\service\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
         $data=Device::where('device_id',$did)->field('video_url')->find();
         return $this->success('请求成功','',$data);
    }
    //站点基本信息
    public function unit(){
        $uid=\app\api\service\Token::getCurrentUid();
        $did=Request::instance()->get('device_id',0);//站点id
        $data=Device::where('device_id',$did)
            ->field('electric_type,protocol,environment,status,device_name,voltage,mark,accendant_name,accendant_department,accendant_email,accendant_mobile,alarm_type')
            ->find();
//        $data['unit']=$uit['mark'];
        return $this->success('请求成功','',$data);
    }
    //管理员
    public function admins(){
        $uid=\app\api\service\Token::getCurrentUid();
        $data=Project_admin::where('id',$uid)
            ->field('name,department,email,phone_number')
            ->find();
        return $this->success('请求成功','',$data);
    }
    //历史曲线-通道信息
    public function channel(){
        $pid=$id = input('param.id/d',0);//通道id
        $info=Passageway::alias('p')->join('
        device d','p.device_id=d.device_id')->with(['passagewayCategory'])->where('p.id',$pid)->field('d.device_name,p.*')->find();
        $data['id']=$info['id'];
        $data['device_id']=$info['device_id'];
        $data['name']=$info['name'];
        $data['device_name']=$info['device_name'];
        $data['change_range_max']=$info['change_range_max'];
        $data['change_range_min']=$info['change_range_min'];
        $data['max_range']=$info['max_range'];
        $data['min_range']=$info['min_range'];
        $data['count_time']=$info['count_time'];
        $data['value']=$info['value'];
        $data['change_value']=$info['change_value'];
        $data['status']=$info['status'];
        $data['a']=$info['a'];
        $data['b']=$info['b'];
        $data['switch_alarm']=$info['switch_alarm'];
        $data['type']=$info['passageway_category']['type'];
        $data['category_name']=$info['passageway_category']['name'];
        return  $this->success('请求成功','',$data);
    }
    //历史曲线
    public function history(){
        $id = input('param.id/d',0);
        $time = input('param.time/s','');
        $info=Passageway::with(['passagewayCategory'])->where('id',$id)->find();
        if ($time)
        {
            list($stime,$etime)=explode(' - ', $time);
            $stime = strtotime($stime);
            $etime = strtotime($etime);
        }else{
            $stime = time()-12*60*60;
            $etime = time();
        }
        if($info['passageway_category']['type']==1){
          return $this->error('无权限');
        }else{
            $array = Device_data::where('dra_id','=',$id)
                ->order('time asc')
                ->where('time','>=',$stime)
                ->where('time','<=',$etime)
                ->select();
            $data = [];
            foreach ($array as $value)
            {
                $data['time'][] = date('Y-m-d H:i',$value['time']);
                $data['data'][] = $value['data'];
            }
            return $this->success('请求成功','',$data);
        }
//        return $this->success('请求成功','',$info);

    }
    //上传日志
    public function addlog(){
        $uid=\app\api\service\Token::getCurrentUid();
        $deviceID = input('post.device_id/s','');
        $img = input('post.img/a',[]);
        $content = input('post.content/s','');
        $res = DeviceLog::create([
            'device_id' => $deviceID,
            'project_id' => $uid,
            'log_info' => $content,
            'img' => $img,
            'time' => time()
        ]);
        if ($res)
        {
            return $this->success('上传成功','');
        }
        else{
            return $this->error('上传失败');
        }
    }


}