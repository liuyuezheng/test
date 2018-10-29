<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 14:31
 */

namespace app\api\controller;


use app\api\model\Device;
use app\api\model\Project_admin;
use app\api\model\Project_admin_device;
use app\api\model\Project_inspect_log;
use think\Request;

class Inspection extends Base
{   //巡检记录
    public function polling(){
        $uid=\app\api\service\Token::getCurrentUid();
//        $uid=3;
        $info=Project_admin::where('id',$uid)->find();
        $date = input('param.date/s','');
        $where = [];
        $where1=[];
        if($date){
            list($stime,$etime)=explode(' - ', $date);
            $where['l.create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
            $where1['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }
        if($info['type']==1){
            $data=Project_admin_device::alias('p')
                ->join('device d','d.device_id=p.device_id')
                ->join('project_inspect_log l','l.project_admin_id=p.project_admin_id')
                ->where($where)
                ->where('d.project_id',$info['p_id'])
                ->order('l.create_time desc')
                ->field('l.*')
                ->select();
        }else{
            $data=Project_inspect_log::where('project_admin_id',$uid)
                ->where($where1)
                ->order('create_time desc')->select();
        }
//        $aa=date('Y-m-d',1535040000);
        return $this->success('请求成功','',$data);
    }
    //搜索巡检记录
    public function checks(){
        $uid=\app\api\service\Token::getCurrentUid();
        $info=Project_admin::where('id',$uid)->find();
        $create_time=Request::instance()->post('create_time',0);//站点id
        $time = strtotime($create_time);
        $start = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));
        $end = mktime(23, 59, 59, date("m", $time), date("d", $time), date("Y", $time));
        $where = [
            'create_time' => ['between', [$start, $end]],
            'project_admin_id' => $uid,
        ];
        $where1=[
            'l.create_time' => ['between', [$start, $end]],

        ];
        if($info['type']==1){
            $data=Project_admin_device::alias('p')
                ->join('device d','d.device_id=p.device_id')
                ->join('project_inspect_log l','l.project_admin_id=p.project_admin_id')
                ->where('d.project_id',$info['p_id'])
                ->where($where1)
                ->field('l.*')
                ->select();
        }else{
            $data=Project_inspect_log::where($where)->select();
        }

        return $this->success('请求成功','',$data);
    }
    //上传记录
    public function addpoll(){
        $uid=\app\api\service\Token::getCurrentUid();
        $img = input('post.img/a',[]);
        $content = input('post.content/s','');
        $info=Project_admin::where('id',$uid)->find();
        if($info['type']==1){
            return $this->error('无权限');
        }
        $res = Project_inspect_log::create([
            'project_admin_id' => $uid,
            'content' => $content,
            'img' => $img,
            'create_time' => time()
        ]);
        $data=[];
        if ($res)
        {
            return $this->success('上传成功','',$data);
        }
        else{
            return $this->error('上传失败');
        }
    }

}