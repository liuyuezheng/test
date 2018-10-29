<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/23
 * Time: 9:54
 */

namespace app\admin\controller;



use app\admin\model\Device;
use app\admin\model\DeviceData;
use app\admin\model\DeviceLog;
use app\admin\model\Passageway;
use app\admin\model\ProjectAdminDevice;
use app\admin\model\Projects;
use app\admin\model\UserToken;
use think\Db;

class SystemOverview extends BaseController
{
    /**
     * @desc 项目列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function projectList()
    {
        $obj = Projects::order('build_start_time desc')
            ->relation(['device'])->select();
        $data = [];
        foreach ($obj as $value)
        {
            $status = 0;
            $count = 0;
            foreach ($value['device'] as $v)
            {
                if ($v['status'] != 0)
                {
                    $status = 1;
                }
                $count += 1;
            }
            $data[] = [
                'id' => $value['id'],
                'project_name' => $value['project_name'],
                'device_count' => $count,
                'build_start_time' => $value['build_start_time'],
                'longitude' => $value['longitude'],
                'latitude' => $value['latitude'],
                'status' => $status
            ];
        }
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 报警列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function alarmList()
    {
        $obj = Projects::order('build_start_time desc')
            ->relation(['device'])->select();
        $data = [];
        foreach ($obj as $value)
        {
            $status = 0;
            $count = 0;
            foreach ($value['device'] as $v)
            {
                if ($v['status'] != 0)
                {
                    $status = 1;
                    $count += 1;
                }

            }
            if ($status == 1)
            {
                $data[] = [
                    'id' => $value['id'],
                    'project_name' => $value['project_name'],
                    'device_exception_count' => $count,
                    'longitude' => $value['longitude'],
                    'latitude' => $value['latitude'],
                ];
            }
        }
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 项目建设
     * @param int $page
     * @param int $size
     * @throws \think\exception\DbException
     */
    public function projectConstruction($page = 1,$size = 10)
    {
        $data = Projects::order('build_start_time desc')
            ->paginate($size,false,['page'=> $page]);

        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 项目信息
     * @param $id
     * @throws \think\exception\DbException
     */
    public function projectSingle()
    {
        $project_id = input('param.project_id/d',0);
        $data = Projects::get($project_id);
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 站点列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function siteList()
    {
        $projectID = input('get.project_id/d',0);
        $data = Device::where('project_id',$projectID)
            ->field(['device_id','device_name','install_last_time','maintain_last_worker','longitude','latitude','status'])
            ->select();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 站点报警列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function siteAlarmList()
    {
        $projectID = input('get.project_id/d',0);
        $data = Device::where('project_id',$projectID)->where('status','neq',0)
            ->field(['device_id','device_name','longitude','latitude','status'])
            ->select();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 通道列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function channelList()
    {
        $deviceID = input('get.device_id/s','');
        $data = Passageway::with(['category'])
            ->where('device_id',$deviceID)
            ->select();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 站点日志
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function siteLog()
    {
        $deviceID = input('get.device_id/s','');
        $data = DeviceLog::with(['projectAdmin'])
            ->order('time desc')->where('device_id',$deviceID)->select();
        foreach ($data as &$value)
            {
                if (!$value['project_admin'])
                {
                    $value['project_admin'] = [];
                }
            }

        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 站点基本信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function siteInfo()
    {
        $deviceID = input('get.device_id/s','');
        $data = Device::where('device_id',$deviceID)->find();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 历史曲线
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function historyCurve()
    {
        $draID = input('param.dra_id/d',0);
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

        $array = DeviceData::where('dra_id','=',$draID)
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

    /**
     * @desc 井盖数据列表
     */
    public function ensorList()
    {
        $wellID = input('get.well_id/s','');
        $data = Db::name('sensor')
            ->where('well_id',$wellID)
            ->select();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 井盖数据日志列表
     */
    public function ensorLog()
    {
        $sensorID = input('get.sensor_id/s','');
        $data = Db::name('well_data a')
//            ->join('left join sensor b on a.well_id=b.id')
            ->join('sensor b','a.well_id=b.id','LEFT')
            ->where('sensor_id',$sensorID)
            ->field('a.*,b.name')
            ->select();
        return $this->success('请求成功','',$data);
    }

    public function passagewaySingle()
    {
        $pass_id = input('param.pass_id/d',0);
        $data = Passageway::with(['device'])
            ->find($pass_id);
        return $this->success('请求成功','',$data);
    }

    public function addLog()
    {
        $uid = UserToken::getCurrentUid();
//        $uid = 1;
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
            return $this->success('上传成功');
        }
        else{
            return $this->error('上传失败');
        }

    }

    public function test()
    {
        $a = 15;
        $b = dechex($a);
        $c = strlen($b);
        $d = '';
        for ($i = 0; $i< 4-$c; $i++)
        {
            $d .= '0';
        }
        $d.=$b;
        return $d;
    }

}