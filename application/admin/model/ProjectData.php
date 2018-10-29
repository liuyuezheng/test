<?php
/**
 * Created by PhpStorm.
 * User: #ZhangQiang
 * Date: 2018/8/30
 * Time: 15:04
 */

namespace app\admin\model;


class ProjectData extends BaseModel
{
    public function category()
    {
        return $this->belongsTo('Category','category_id','id');
    }

    public function getCoverAttr($value)
    {
        return $this->prefixImgUrl($value);
    }

    public function getContentAttr($value)
    {
        return $this->prefixImgUrl($value);
    }
}