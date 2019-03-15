<?php

namespace app\admin\model;

use think\Model;
use think\Session;

class CatchLists extends Model
{
    //设置数据库表名
    //protected $table = 'admin';
    //设置主键
    protected $pk = ['auto_id', 'Id'];

//查询是否有重复订单
    public function findById($Id)
    {
        $res = $this->where('Id',$Id)->find();
        return $res;
    }
}