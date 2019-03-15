<?php

namespace app\checkgbk\model;

use think\Model;
use think\Session;

class BuildOrder extends Model
{
    //设置数据库表名
    //protected $table = 'admin';
    //设置主键
    protected $pk = 'auto_Id';
    protected $autoWriteTimestamp = true;

    public function maxid(){
        $maxid = $this->max('Id');
        return $maxid;
    }


}