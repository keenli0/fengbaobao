<?php

namespace app\checkgbk\model;

use think\Model;
use think\Session;

class Config extends Model
{
    //设置数据库表名
    //protected $table = 'admin';
    //设置主键
    protected $pk = 'id';

    // 设置当前模型的数据库连接

    /**
     * @return mixed
     */
    public function getset($data)
    {
        return $this->where("key",$data)->value('value');
    }

}