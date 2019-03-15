<?php

namespace app\checkgbk\model;

use think\Model;
use think\Session;

class CatchLists extends Model
{
    //设置数据库表名
    //protected $table = 'admin';
    //设置主键
    protected $pk = 'auto_Id';
    protected $autoWriteTimestamp = true;

    public function maxid()
    {
        $maxid = $this->max('Id');
        return $maxid;
    }

    public function isCheck($arr)
    {
        foreach ($arr as $k => $v) {
            $res = $this->where('Id', $v)->update(['is_check' => '1']);
        }

        return 1;
    }
}