<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/9/18
 * Time: 15:05
 */

namespace app\api\model;

use think\Model;

class Order extends Model
{
    protected $pk = 'id';

    //查询是否有重复订单
    public function findById($Id)
    {
        $res = $this->where('Id', $Id)->find();
        return $res;
    }

    public function getOneById($arr, $data = '')
    {
        $res = $this->where($arr)->find();
        return $res;
    }
    public function getListBywhere($arr, $data = '')
    {
        $res = $this->where($arr)->select();
        return $res;
    }

    public function saveAisle($id, $data)
    {
        $res = $this->where($id)
            ->update($data);
        return $res;
    }
}