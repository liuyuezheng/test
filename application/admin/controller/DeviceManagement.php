<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/27
 * Time: 16:06
 */

namespace app\admin\controller;


use app\admin\model\Device;
use app\admin\model\DeviceCorrelation;
use app\admin\model\DeviceRegisterAlias;
use app\admin\model\Passageway;
use app\admin\model\PassagewayCategory;
use app\admin\model\PassagewayStartAddress;
use app\admin\model\Projects;
use app\admin\model\ReadDevice;
use app\admin\model\Sensor;
use app\admin\model\SwitchList;
use app\admin\model\Well;
use think\Db;

class DeviceManagement extends BaseController
{
    /**
     * @desc 项目列表
     * @param int $page
     * @param int $size
     * @throws \think\exception\DbException
     */
    public function projectList($page = 1, $size = 10)
    {
        $keywords = input('param.keywords/s','');
        $where = [];
        if ($keywords)
        {
            $where['project_name|province'] = ['like',"%$keywords%"];
        }
        $data = Projects::order('build_start_time desc')
            ->where($where)
            ->paginate($size,false,['page'=>$page]);
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 添加项目
     */
    public function addProject()
    {
        $projectName = input('post.project_name/s','');
        $projectLogo = input('post.project_logo/s','');
        $province = input('post.province/s','');
        $longitude = input('post.longitude/s','');
        $latitude = input('post.latitude/s','');
        $projectExplain = input('post.project_explain/s','');

        $res = Projects::create([
            'project_name' => $projectName,
            'build_start_time' => time(),
            'maintain_last_time' => time(),
            'project_explain' => $projectExplain,
            'logo' => $projectLogo,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'province' => $province
        ]);
        if ($res)
        {
            return $this->success('添加成功');
        }
        else{
            return $this->error('添加失败');
        }
    }

    /**
     * @desc 编辑项目
     * @throws \think\exception\DbException
     */
    public function editProject()
    {
        $id = input('post.project_id/d',0);
        $projectName = input('post.project_name/s','');
        $projectLogo = input('post.project_logo/s','');
        $province = input('post.province/s','');
        $longitude = input('post.longitude/s','');
        $latitude = input('post.latitude/s','');
        $projectExplain = input('post.project_explain/s','');

        $model = Projects::get($id);

        $model->project_name = $projectName;
        $model->project_explain = $projectExplain;
        if ($projectLogo)
        {
            $model->logo = $projectLogo;
        }
        $model->longitude = $longitude;
        $model->latitude = $latitude;
        $model->province = $province;
        $res = $model->save();

        if ($res)
        {
            return $this->success('编辑成功');
        }
        else{
            return $this->error('编辑失败');
        }
    }

    /**
     * @desc 删除项目
     */
    public function deleteProject()
    {
        $project_id = input('post.project_id/d',0);
        $device_ids = Device::where('project_id',$project_id)->column('device_id');
        Passageway::where('device_id','in',$device_ids)->delete();
        Device::destroy($device_ids);
        Projects::destroy($project_id);
        return $this->success('删除成功');
    }

    /**
     * @desc 站点列表
     * @param int $page
     * @param int $size
     * @throws \think\exception\DbException
     */
    public function deviceList($page = 1, $size = 10)
    {
        $keywords = input('param.keywords/s','');
        $where = [];
        if ($keywords)
        {
            $where['device_id|device_name'] = ['like',"%$keywords%"];
        }
        $array = Device::with(['project'])
            ->order('install_last_time desc')
            ->where($where)
            ->paginate($size,false,['page'=> $page])
            ->toArray();

        $data = [];
        $data['total'] = $array['total'];
        $data['per_page'] = $array['per_page'];
        $data['current_page'] = $array['current_page'];
        $data['last_page'] = $array['last_page'];
        foreach ($array['data'] as $value)
        {
            $data['data'][] = [
                'device_id' => $value['device_id'],
                'device_name' => $value['device_name'],
                'project_name' => $value['project']['project_name'],
                'province' => $value['project']['province'],
                'electric_type' => $value['electric_type'],
                'protocol' => $value['protocol'],
                'environment' => $value['environment'],
                'accendant_name' => $value['accendant_name'],
                'accendant_department' => $value['accendant_department'],
                'accendant_email' => $value['accendant_email'],
                'accendant_mobile' => $value['accendant_mobile']
            ];
        }
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 添加站点
     */
    public function addDevice()
    {
        $deviceName = input('post.device_name/s','');
        $deviceID = input('post.device_id/s','');
        $projectID = input('post.project_id/d',0);
        $electricType = input('post.electric_type/s','');
        $voltage = input('post.voltage/s','');
        $protocol = input('post.protocol/d',0);
        $mark = input('post.mark/s','');
        $environment = input('post.environment/s','');
        $accendant_name = input('post.accendant_name/s','');
        $accendant_department = input('post.accendant_department/s','');
        $accendant_email = input('post.accendant_email/s','');
        $accendant_mobile = input('post.accendant_mobile/s','');
        $longitude = input('post.longitude/s','');
        $latitude = input('post.latitude/s','');
        $video_url = input('post.video_url/s','');
        $is_old = input('post.is_old/d',0);

        $modelA = Device::get($deviceID);
        if ($modelA)
        {
            return $this->error('编号重复');
        }

        $model = new Device();
        $model->device_name = $deviceName;
        $model->device_id = $deviceID;
        $model->project_id = $projectID;
        $model->electric_type = $electricType;
        $model->voltage = $voltage;
        $model->protocol = $protocol;
        $model->mark = $mark;
        $model->environment = $environment;
        $model->accendant_name = $accendant_name;
        $model->accendant_department = $accendant_department;
        $model->accendant_email = $accendant_email;
        $model->accendant_mobile = $accendant_mobile;
        $model->longitude = $longitude;
        $model->latitude = $latitude;
        $model->video_url = $video_url;
        $model->is_old = $is_old;
        $model->install_last_time = time();
        $res = $model->save();

        $readDeviceModel = new ReadDevice();
        $readDeviceModel->device_id = $deviceID;
        $readDeviceModel->mark = $mark;
        $readDeviceModel->save();

        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }

    }

    /**
     * @desc 编辑站点
     */
    public function editDevice()
    {
        $deviceName = input('post.device_name/s','');
        $deviceID = input('post.device_id/s','');
        $projectID = input('post.project_id/d',0);
        $electricType = input('post.electric_type/s','');
        $voltage = input('post.voltage/s','');
        $protocol = input('post.protocol/d',0);
        $mark = input('post.mark/s','');
        $environment = input('post.environment/s','');
        $accendant_name = input('post.accendant_name/s','');
        $accendant_department = input('post.accendant_department/s','');
        $accendant_email = input('post.accendant_email/s','');
        $accendant_mobile = input('post.accendant_mobile/s','');

        $model = Device::get($deviceID);
        $model->device_name = $deviceName;
        $model->device_id = $deviceID;
        $model->project_id = $projectID;
        $model->electric_type = $electricType;
        $model->voltage = $voltage;
        $model->protocol = $protocol;
        $model->mark = $mark;
        $model->environment = $environment;
        $model->accendant_name = $accendant_name;
        $model->accendant_department = $accendant_department;
        $model->accendant_email = $accendant_email;
        $model->accendant_mobile = $accendant_mobile;
        $res = $model->save();

        if ($res)
        {
            return $this->success('编辑成功');
        }else{
            return $this->error('编辑失败');
        }

    }

    /**
     * @desc 报警设置
     * @throws \think\exception\DbException
     */
    public function alarmSetting()
    {
        $deviceID = input('post.device_id/s','');
        $alarm_communication_time = input('post.alarm_communication_time/d',1);
        $alarm_type = input('post.alarm_type/d',0);

        $model = Device::get($deviceID);
        $model->alarm_communication_time = $alarm_communication_time;
        $model->alarm_type = $alarm_type;
        $res = $model->save();
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    /**
     * @desc 通讯设置
     * @throws \think\exception\DbException
     */
    public function communicationSetting()
    {
        $deviceID = input('post.device_id/s','');
        $communicationdistance = input('post.communicationdistance/d',1);
        $ip = input('post.ip/s','');
        $port_number = input('post.port_number/s','');
        $work_pattern = input('post.work_pattern/s','');
        $reset_logo = input('post.reset_logo/s','');
        $switch = input('post.function_switch/s','');
        $initial_channel = input('post.initial_channel/s','');
        $measurement_channel_number = input('post.measurement_channel_number/d',0);

        $model = Device::get($deviceID);
        $model->communicationdistance = $communicationdistance;
        $model->ip = $ip;
        $model->port_number = $port_number;
        $model->work_pattern = $work_pattern;
        $model->reset_logo = $reset_logo;
        $model->switch = $switch;
        $model->initial_channel = $initial_channel;
        $model->measurement_channel_number = $measurement_channel_number;
        $res = $model->save();
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    public function deleteDevice()
    {
        $device_id = input('post.device_id/s','');
        Device::destroy($device_id);
        Passageway::where('device_id',$device_id)->delete();
        return $this->success('删除成功');
    }

    /**
     * @desc 通道列表
     * @throws \think\exception\DbException
     */
    public function passList()
    {
        $page = input('param.page/d',1);
        $size = input('param.size/d',10);
        $device_id = input('param.device_id/s','');

        $where = [];
        $where['device_id'] = ['=',$device_id];

        $device = Device::get($device_id);
        $data = Passageway::order('id desc')
            ->with(['category'])
            ->where($where)
            ->paginate($size,false,['page'=> $page])
            ->toArray();
        $data['device_name'] = $device['device_name'];
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 新增通道
     */
    public function addPass()
    {
        $passageways = input('post.passageways/a',[]);

//        $model = new Passageway();
//        $res = $model->save($passageways);

        $addData = [];
        $addData['device_id'] = $passageways[0]['device_id'];
        $addData['a'] = $passageways[0]['a'];
        $addData['b'] = $passageways[0]['b'];

        $data = [];
        foreach ($passageways as $value)
        {
            $value['start_coding'] = '00'.$value['y'].$value['x'];
            $model = new Passageway();
            $model->save($value);
            $cate_id = $value['category_id'];
            $cate = PassagewayCategory::get($cate_id);
            $type = $cate['type'];
            $data[] = [
                'id' => $model->id,
                'starting_address' => $value['start_coding']
            ];
        }

        array_multisort(array_column($data,'id'),SORT_ASC,$data);

        $addData['passageway_id'] = implode(',',array_column($data,'id'));
        $addData['starting_address'] = $data[0]['starting_address'];
        $count = count($passageways);
        $b = dechex($count);
        $c = strlen($b);
        $d = '';
        for ($i = 0; $i< 4-$c; $i++)
        {
            $d .= '0';
        }
        $d.=$b;
        $addData['register_number'] = $d;
        $addData['type'] = $type;
        $addData['mark'] = $passageways[0]['mark'];
        $modelB = new DeviceRegisterAlias();
        $modelB->save($addData);

        $modelC = ReadDevice::where('device_id',$passageways[0]['device_id'])->find();
        if (!$modelC)
        {
            $modelC = new ReadDevice();
        }
        $modelC->start_address = $addData['starting_address'];
        $modelC->register_number = $d;
        $modelC->save();
        return $this->success('添加成功');

    }

    /**
     * @desc 修改通道
     * @throws \think\exception\DbException
     */
    public function editPass()
    {
        $name = input('post.name/s','');
        $a = input('post.a/d',1);
        $b = input('post.b/d',0);
        $switch_alarm = input('post.switch_alarm/d',0);
        $id = input('post.id/d',0);

        $model = Passageway::get($id);
        $model->name = $name;
        $model->a = $a;
        $model->b = $b;
        $model->switch_alarm = $switch_alarm;
        $res = $model->save();

        if ($res)
        {
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }
    }

    public function deletePass()
    {
        $mark = input('post.mark/s','');
        $res = Passageway::where('mark',$mark)->delete();
        DeviceRegisterAlias::where('mark',$mark)->delete();
        if ($res)
        {
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    /**
     * @desc 通道类型列表
     * @throws \think\exception\DbException
     */
    public function categoryList()
    {
        $data = PassagewayCategory::all();
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 添加通道类型
     */
    public function addCategory()
    {
        $name = input('post.name/s','');
        $type = input('post.type/d',0);
        $data_address = input('post.data_address/s','');

        $model = new PassagewayCategory();
        $model->name = $name;
        $model->type = $type;
        $model->data_address = $data_address;
        $res = $model->save();
        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }

    /**
     * @desc 编辑通道类型
     * @throws \think\exception\DbException
     */
    public function editCategory()
    {
        $id = input('post.id/d',0);
        $name = input('post.name/s','');
        $type = input('post.type/d',0);
        $data_address = input('post.data_address/s','');

        $model = PassagewayCategory::get($id);
        $model->name = $name;
        $model->type = $type;
        $model->data_address = $data_address;
        $res = $model->save();
        if ($res)
        {
            return $this->success('编辑成功');
        }else{
            return $this->error('编辑失败');
        }
    }

    /**
     * @desc 删除通道类型
     */
    public function deleteCategory()
    {
        $id = input('post.id/d',0);
        $res = PassagewayCategory::destroy($id);
        if ($res)
        {
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    /**
     * @desc 限制设置
     * @throws \think\Exception
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function limitSetting()
    {
        $pass_id = input('post.pass_id/d',0);
        $min_range = input('post.min_range/s','');
        $max_range = input('post.max_range/s','');
        $change_range_min = input('post.change_range_min/s','');
        $change_range_max = input('post.change_range_max/s','');
        $alarm_limit_min = input('post.alarm_limit_min');
        $alarm_limit_max = input('post.alarm_limit_max');
        $a = input('post.a/s','');
        $b = input('post.b/s','');

        $passageways = input('post.passageway_id/d',0);
        $mark = input('post.mark/s','');
        $code = input('post.code/s','');
        $address_code = input('post.address_code/s','');
        $value = input('post.value/s','');
        if ($alarm_limit_min && $alarm_limit_max && $passageways)
        {
            Db::name('PassagewayCorrelation')->where('passageway1_id|passageway2_id',$pass_id)->delete();

                $addData[] = [
                    'passageway1_id' => $pass_id,
                    'passageway2_id' => $passageways,
                    'alarm_limit_min' => $alarm_limit_min,
                    'alarm_limit_max' => $alarm_limit_max,
                    'a' => $a,
                    'b' => $b
                ];
            Db::name('PassagewayCorrelation')->insertAll($addData);
        }

        $model = Passageway::get($pass_id);
        $model->min_range = $min_range;
        $model->max_range = $max_range;
        $model->change_range_min = $change_range_min;
        $model->change_range_max = $change_range_max;
        $model->mark = $mark;
        $model->code = $code;
        $model->address_code = $address_code;
        $model->value_num = $value;
        $res = $model->save();
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    public function registerList()
    {
        $type = input('param.type/d',-1);
        $data = PassagewayStartAddress::order('id asc')->where('type',$type)->select();
        return $data;
    }

    public function addRegister()
    {
        $y = input('post.y','');
        $x = input('post.x','');
        $data = input('post.data/s','');
        $type = 0;
        $model = new PassagewayStartAddress();
        $model->address_y = $y;
        $model->address_y = $x;
        $model->data = $data;
        $model->type = $type;
        $res = $model->save();
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    public function video($device_id)
    {
        $res = Device::get($device_id);
        $url = $res['video_url'];
        $url = explode(',',$url);
        return ['url'=>$url];
    }

    public function deviceByProject($device_id)
    {
        $model = Device::get($device_id);
        $res = Device::where('project_id',$model->project_id)->select();
        return $res;
    }

    public function relationDevice($device_id1,$device_id2)
    {
        $where = [];
        $where['device_id1|device_id2'] = ['=',$device_id1];
        $where['device_id1|device_id2'] = ['=',$device_id2];
        DeviceCorrelation::where($where)
            ->delete();
        $model1 = Device::get($device_id1);
        $model2 = Device::get($device_id2);
        $res = DeviceCorrelation::create([
            'device_id1' => $device_id1,
            'device_id2' => $device_id2,
            'lon1' => $model1->longitude,
            'lat1' => $model1->latitude,
            'lon2' => $model2->longitude,
            'lat2' => $model2->latitude,
            'project_id' => $model1->project_id
        ]);
        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    public function getRelationDevice($project_id)
    {
        $res = DeviceCorrelation::where('project_id',$project_id)->select();
        $data = [];
        foreach ($res as $value)
        {
            $data[] = [
                [
                    'lng' => $value['lon1'],
                    'lat' => $value['lat1']
                ],
                [
                    'lng' => $value['lon2'],
                    'lat' => $value['lat2']
                ]
            ];
        }
        return $data;
    }

    /**
     * @throws \think\exception\DbException
     * @desc 开关控制
     */
    public function switchSetting()
    {

        $device_id = input('post.device_id/s','');
        $name = input('post.name/s','');
        $status = input('post.status/d',0);
        $mark = input('post.mark/s','');
        $code = input('post.code/s','');
        $address_code = input('post.address_code/s','');
        $value = input('post.value/s','');


        $model = new SwitchList();

        $model->device_id = $device_id;
        $model->name = $name;
        $model->mark = $mark;
        $model->code = $code;
        $model->address_code = $address_code;
        $model->value = $value;
        $model->status = $status;
        $res = $model->save();


        if ($res)
        {
            return $this->success('设置成功');
        }else{
            return $this->error('设置失败');
        }
    }

    public function switchList($device_id)
    {
        $model = new SwitchList();
        $data = $model->where('device_id',$device_id)->select();
        return $data;
    }


    public function deleteSwitch($id)
    {
        $model = new SwitchList();
        $res = $model->where('id',$id)->delete();
        if ($res)
        {
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    public function addWell()
    {
        $project_id = input('post.project_id/s','');
        $name = input('post.name/s','');
        $lng = input('post.lng/s','');
        $lat = input('post.lat/s','');
        $fangwei = input('post.fangwei/s','');
        $linkman = input('post.linkman/s','');
        $department = input('post.department/s','');
        $email = input('post.email/s','');
        $phone = input('post.phone/s','');

        $model = new Well();
        $model->project_id = $project_id;
        $model->name = $name;
        $model->lng = $lng;
        $model->lat = $lat;
        $model->fangwei = $fangwei;
        $model->linkman = $linkman;
        $model->department = $department;
        $model->email = $email;
        $model->phone = $phone;
        $res = $model->save();
        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }


    public function wellList()
    {
        $model = new Well();
        $data = $model->relation(['project'])->select();
        return $data;
    }

    public function deletewell($id)
    {
        $model = new Well();
        $res = $model->where('id',$id)->delete();
        if ($res)
        {
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    public function addSensor()
    {
        $well_id = input('post.well_id/d',0);
        $name = input('post.name/s','');
        $device_id = input('post.device_id/s','');

        $model = new Sensor();
        $model->well_id = $well_id;
        $model->name = $name;
        $model->device_id = $device_id;
        $res = $model->save();
        if ($res)
        {
            return $this->success('添加成功');
        }else{
            return $this->error('添加失败');
        }
    }

    public function sensorList($well_id)
    {
        $data = Sensor::where('well_id',$well_id)->select();
        return $data;
    }

    public function deletesensor($id)
    {
        $model = new Sensor();
        $res = $model->where('id',$id)->delete();
        if ($res)
        {
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    public function switchRequest($switch_id, $flag)
    {
        $info = SwitchList::get($switch_id);
        if ($flag){
            $value = 'FF00';
        }else
        {
            $value = '0000';
        }
        $msg = $readSendData = '00000000' . "0006" . $info['mark'] . '05' . $info['address_code'] . $value;
        $url = "http://xhgj.mumarenkj.com/worker/sendmsg.php";
        $postData = [
            'DeviceId' => $info['device_id'],
            'Msg' => $msg
        ];
        $rs = $this->curlPost($url, $postData);
//        return $rs;
        if ($rs!='fail\n') {
            // SwitchList::where('id', $switch_id)->update(['status'=>$flag]);
            return $this->success('操作成功');
        } else {
            return $this->error('操作失败');
        }
    }

    public static function curlPost($url, $postData = array(), $header = array())
    {
        try{
            //初始化请求句柄
            $curl = curl_init();

            if (!empty($header)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                curl_setopt($curl, CURLOPT_HEADER, true);
            } else {
                curl_setopt($curl, CURLOPT_HEADER, false);
            }

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  //显示输出结果
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 60);
            curl_setopt($curl, CURLOPT_USERPWD, "BDADCBD5CAA1CCA9D6DDD6D0D1A7:6EE2040D64323362");
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
            $result = curl_exec($curl);
            if (curl_errno($curl)) {
                return false;
            }

            curl_close($curl);
            return $result;
        }catch (\Exception $e){
            return $e;
        }

    }
}