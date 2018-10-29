<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 8:55
 */

namespace app\api\controller;


use app\api\model\Device;
use app\api\model\Device_data;
use app\api\model\Passageway;
use app\api\model\PassagewayCategory;
use app\api\model\Project_admin;
use app\api\model\Project_admin_device;
use think\Request;

class Statistical extends Base
{
   //统计分析-故障筛查
    public function fault($page=1,$size=10){
        $uid=\app\api\service\Token::getCurrentUid();//登录id
//        $uid=Request::instance()->get('aid',0);
        $res=Project_admin::where('id',$uid)->find();
        $pass_id=input('param.pass_id/d',0);
        $device_id=input('param.device_id/s','');
        $status = input('param.status/d',0);
//        $device_id = input('param.device_id/s','');
        $where = [];
//        $where1=[];
        if ($pass_id){
//            $where1['id']=['=',$id];
            $where['passageway.id']= ['=',$pass_id];
        }
        if($device_id){
//            $where1['device_id']= ['=',$device_id];
            $where['device.device_id']= ['=',$device_id];
        }
        if($status){
//            $where1['status']=['=',$status];
            $where['device.status']=['=',$status];
            $where['passageway.status']=['=',$status];
        }else{
//            $where1['status']=['in',[1,2]];
            $where['device.status']=['in',[1,2]];
            $where['passageway.status']=['in',[1,2]];
        }
        if($res['type']==1){
              $info=Passageway::join('device','device.device_id=passageway.device_id')
                  ->where('device.project_id',$res['p_id'])
                  ->where($where)
                  ->paginate($size,false,['page'=>$page])
                  ->toArray();
            $data['total']=$info['total'];
            $data['per_page']=$info['per_page'];
            $data['current_page']=$info['current_page'];
            $data['last_page']=$info['last_page'];
            foreach ($info['data'] as $value){
                $data['data'][]=[
                    'id'=>$value['id'],
                    'device_id' => $value['device_id'],
                    'name' => $value['name'],
                    'device_name' => $value['device_name'],
                    'status' => $value['status']
                ];
            }
        }else{
            $device=Project_admin_device::where('project_admin_id',$uid)->select();
                foreach ($device as $v){
                    $info=Device::join('passageway','device.device_id=passageway.device_id')
                        ->where('device.device_id',$v['device_id'])
                        ->where($where)
                        ->paginate($size,false,['page'=>$page])
                        ->toArray();
                }
            $data['total']=$info['total'];
            $data['per_page']=$info['per_page'];
            $data['current_page']=$info['current_page'];
            $data['last_page']=$info['last_page'];
            foreach ($info['data'] as $value){
                $data['data'][]=[
                    'id'=>$value['id'],
                    'device_id' => $value['device_id'],
                    'name' => $value['name'],
                    'device_name' => $value['device_name'],
                    'status' => $value['status']
                ];
            }
        }
        return $this->success('请求成功','',$data);
    }
    //站点列表
    public function devices()
    {   $uid=\app\api\service\Token::getCurrentUid();//登录id
//        $uid=Request::instance()->get('aid',0);
        $res=Project_admin::where('id',$uid)->find();
        if($res['type']==1){
            $data = Device::order('install_last_time desc')
                ->where('project_id','=',$res['p_id'])
                ->field(['device_id','device_name'])
                ->select();
        }else{
            $data=Device::join('project_admin_device p','p.device_id=device.device_id')
                ->order('device.install_last_time desc')
                ->where('p.project_admin_id',$uid)
                ->field(['device_id','device_name'])
                ->select();
        }

        return $this->success('请求成功','',$data);
    }
    //通道列表
    public function pass($device_id=''){
        $data = Passageway::order('id desc')
            ->where('device_id','=',$device_id)
            ->field(['id','name'])
            ->select();
        return $this->success('请求成功','',$data);
    }
    //通道类别联动
    public function cate(){
       $data=PassagewayCategory::select();
       return $this->success('请求成功','',$data);
    }
    //站点-通道联动
    public function site($category_id=''){
        $data=Passageway::join('device','device.device_id=passageway.device_id')
            ->where('passageway.category_id',$category_id)
            ->field('passageway.id,passageway.name,device.device_name')
            ->select();
        return $this->success('请求成功','',$data);
    }
    //曲线对比
    public function historys(){
        $pid = input('param.pid/a',[]);
        $time = input('param.time/s','');
        if ($time)
        {
            list($stime,$etime)=explode(' - ', $time);
            $stime = strtotime($stime);
            $etime = strtotime($etime);
        }else{
            $stime = time()-12*60*60;
            $etime = time();
        }
        $datas = [];
        foreach ($pid as $id)
        {
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
            $datas[] = $data;
        }
        return $this->success('请求成功','',$datas);
    }

}