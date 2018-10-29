<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/30
 * Time: 17:18
 */

namespace app\admin\controller;


use app\admin\model\ProjectAdmin;
use app\admin\model\ProjectAdminDevice;
use app\library\ApiException;

class AccountManagement extends BaseController
{
    public function  adminList()
    {
        $search = input('param.search/s','');
        $page = input('param.page/d',1);
        $size = input('param.size/d',10);

        $where = [];
        $where['type'] = ['=',1];
        if ($search)
        {
            $where['name|account_number'] = ['like',"%$search%"];
        }

        $model = new ProjectAdmin();
        $data = $model->relation('project')
            ->where($where)
            ->field(['id','account_number','name','create_time','p_id'])
            ->paginate($size,false,['page' => $page])->toArray();

        foreach ($data['data'] as &$value)
        {
            if (!$value['project'])
            {
                $value['project'] = [];
            }
        }

        return $this->success('请求成功','',$data);
    }

    public function  inspectorList()
    {
        $search = input('param.search/s','');
        $page = input('param.page/d',1);
        $size = input('param.size/d',10);

        $where = [];
        $where['type'] = ['=',2];
        if ($search)
        {
            $where['name|account_number'] = ['like',"%$search%"];
        }

        $model = new ProjectAdmin();
        $array = $model->relation(['project','device'])
            ->where($where)
            ->field(['account_number','name','create_time','p_id','id'])
            ->paginate($size,false,['page' => $page])->toArray();
        $data = [];
        foreach ($array['data'] as $value)
        {
            $device = [];
            foreach ($value['device'] as $v)
            {
                $device[] = $v['device_name'];
            }
            $data[] = [
                'id' => $value['id'],
                'account_number' => $value['account_number'],
                'name' => $value['name'],
                'project' => $value['project']['project_name'],
                'device' => $device,
                'create_time' => $value['create_time']
            ];
        }
        $array['data'] = $data;
        return $this->success('请求成功','',$array);
    }

    public function addAdmin()
    {
        $name = input('post.name/s','');
        $type = input('post.type/d',1);
        $account_number = input('post.account_number/s','');
        $password = input('post.password/s','');

        $res = ProjectAdmin::where('account_number',$account_number)->find();
        if ($res)
        {
            throw new ApiException(['msg' => '账号已存在']);
        }
        else{
            $model = new ProjectAdmin();
            $model->name = $name;
            $model->type = $type;
            $model->account_number = $account_number;
            $model->password = $password;
            $model->save();
            return $this->success('添加成功');
        }
    }

    public function assignProject()
    {
        $admin_id = input('post.admin_id/d',0);
        $project_id = input('post.project_id/d',0);
        $model = ProjectAdmin::get($admin_id);
        $model->p_id = $project_id;
        $model->save();
        return $this->success('分配成功');
    }

    public function assignDevice()
    {
        $admin_id = input('post.admin_id/d',0);
        $device_ids = input('post.device_ids/s','');
        $device_ids = explode(',',$device_ids);
        $addData = [];
        foreach ($device_ids as $value)
        {
            $addData[] = [
                'device_id' => $value,
                'project_admin_id' => $admin_id
            ];
        }
        $model = new ProjectAdminDevice();
        $model->where('project_admin_id',$admin_id)->delete();
        $model->insertAll($addData);
        return $this->success('分配成功');
    }

    public function repassword()
    {
        $admin_id = input('post.admin_id/d',0);
        $password = input('post.password/s','');
        $model = ProjectAdmin::get($admin_id);
        $model->password = $password;
        $model->save();
        return $this->success('重置成功');
    }

    public function deleteAdmin()
    {
        $admin_id = input('post.admin_id/d',0);
        $res = ProjectAdmin::destroy($admin_id);
        if ($res)
        {
            return $this->success('注销成功');
        }
        else{
            return $this->error('注销失败');
        }
    }
}