<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 11:23
 */

namespace app\admin\controller;


use app\admin\model\Device;
use app\admin\model\DeviceData;
use app\admin\model\Passageway;
use app\admin\model\PassagewayCategory;
use app\admin\model\Projects;

class StatisticalAnalysis extends BaseController
{
    /**
     * @desc 故障筛查
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function breakdown($page = 1, $size = 10)
    {
        $type = input('param.type/d',0);
        $project_id = input('param.project_id/d',0);
        $device_id = input('param.device_id/s','');
        $dra_id = input('param.dra_id/d',0);

        $where = [];
        if ($type)
        {
            $where['status'] = ['=',$type];
        }else{
            $where['status'] = ['in',[1,2]];
        }

        if ($project_id)
        {
            $deviceIDs = Device::where('project_id','=',$project_id)->column('device_id');
            $where['device_id'] = ['in',$deviceIDs];
        }

        if ($device_id)
        {
            $where['device_id'] = ['=',$device_id];
        }

        if ($dra_id)
        {
            $where['id'] = ['=',$dra_id];
        }

        $obj = Passageway::with(['device','device.project'])
            ->order('id asc')
            ->where($where)
            ->paginate($size,false,['page'=> $page])
            ->toArray();
        $data['total'] = $obj['total'];
        $data['per_page'] = $obj['per_page'];
        $data['current_page'] = $obj['current_page'];
        $data['last_page'] = $obj['last_page'];
        $data['data'] = [];
        foreach ($obj['data'] as $value)
        {
            $data['data'][] = [
                'id' => $value['id'],
                'passageway_name' => $value['name'],
                'device_name' => $value['device']['device_name'],
                'project_name' => $value['device']['project']['project_name'],
                'status' => $value['status']
            ];
        }
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 获取项目列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProjectList()
    {
        $data = Projects::order('build_start_time desc')
            ->field(['id','project_name'])
            ->select();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 获取站点列表
     * @param int $project_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDeviceList($project_id = 0)
    {
        $data = Device::order('install_last_time desc')
            ->where('project_id','=',$project_id)
            ->field(['device_id','device_name'])
            ->select();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 获取通道列表
     * @param string $device_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getPassagewayList($device_id = '')
    {
        $data = Passageway::order('id desc')
            ->where('device_id','=',$device_id)
            ->field(['id','name'])
            ->select();
        return $this->success('请求成功','',$data);
    }

    public function historicalCurve()
    {
        $pass_ids = input('param.pass_ids/a',[]);
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
        foreach ($pass_ids as $pass_id)
        {
            $array = DeviceData::where('dra_id','=',$pass_id)
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

    public function getCategory()
    {
        $data = PassagewayCategory::all();
        return $this->success('请求成功','',$data);
    }

    public function getPassagewayByCategory()
    {
        $category_id = input('param.category_id/d',0);
        $data = Passageway::where('category_id','=',$category_id)->field(['id','name'])->select();
        return $this->success('请求成功','',$data);
    }
}