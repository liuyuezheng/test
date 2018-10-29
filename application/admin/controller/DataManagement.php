<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/30
 * Time: 15:06
 */

namespace app\admin\controller;


use app\admin\model\Category;
use app\admin\model\ProjectData;

class DataManagement extends BaseController
{
    /**
     * @desc 资料列表
     * @throws \think\exception\DbException
     */
    public function dataList()
    {
        $page = input('param.page/d',1);
        $size = input('param.size/d',10);

        $data = ProjectData::with(['category'])
            ->order('create_time desc')
            ->paginate($size,false,['page' => $page]);
        return $this->success('请求成功','',$data);
    }

    /**
     * @desc 上传资料
     */
    public function addData()
    {
        $title = input('post.title/s','');
        $category_id = input('post.category_id/d',0);
        $content = input('post.content/s','');
        $model = new ProjectData();
        $model->title = $title;
        $model->category_id = $category_id;
        $model->content = $content;
        $model->create_time = time();
        $res = $model->save();
        if ($res)
        {
            return $this->success('上传成功');
        }else{
            return $this->error('上传失败');
        }
    }

    /**
     * @desc 修改资料
     * @throws \think\exception\DbException
     */
    public function editData()
    {
        $id = input('post.id/d',0);
        $title = input('post.title/s','');
        $category_id = input('post.category_id/d',0);
        $content = input('post.content/s','');
        $model = ProjectData::get($id);
        $model->title = $title;
        $model->category_id = $category_id;
        if ($content)
        {
            $model->content = $content;
        }
        $res = $model->save();
        if ($res)
        {
            return $this->success('修改成功');
        }else{
            return $this->error('修改失败');
        }
    }

    /**
     * @desc 删除资料
     */
    public function deleteData()
    {
        $id = input('post.id/d',0);
        $res = ProjectData::destroy($id);
        if ($res)
        {
            return $this->success('删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

    /**
     * @desc 添加区域
     */
    public function addCategory()
    {
        $name = input('post.name/s','');
        $model = new Category();
        $model->name = $name;
        $res = $model->save();
        if ($res)
        {
            return $this->success('添加成功','',['id'=>$model->id]);
        }else{
            return $this->error('添加失败');
        }
    }

    public function categoryList()
    {
        $data = Category::all();
        return $this->success('请求成功','',$data);
    }

    public function download()
    {
        $name = input('param.name/s','');


//判断如果文件存在,则跳转到下载路径
        if(file_exists($name)){
            header('location:http://'.$name);
        }else{
            header('HTTP/1.1 404 Not Found');
        }
    }
}