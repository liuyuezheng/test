<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/30
 * Time: 16:52
 */

namespace app\admin\controller;


use app\admin\model\ProjectAdmin;
use app\admin\model\ProjectInspectLog;

class InspectionRecord extends BaseController
{
    public function lists()
    {
        $page = input('param.page/d',1);
        $size = input('param.size/d',10);
        $project_id = input('param.project_id/d',0);
        $date = input('param.date/s','');

        $where = [];
        if ($project_id)
        {
            $ids = ProjectAdmin::where('p_id',$project_id)
                ->where('type',2)
                ->column('id');
            $where['project_admin_id'] = ['in',$ids];
        }
        if($date)
        {
            list($stime,$etime)=explode(' - ', $date);
            $where['create_time']=[['>',strtotime($stime)], ['<', strtotime($etime)]];
        }

        $model = new ProjectInspectLog();
        $data = $model->order('create_time desc')
            ->where($where)
            ->paginate($size,false,['page' => $page]);
        return $this->success('请求成功','',$data);
    }
}