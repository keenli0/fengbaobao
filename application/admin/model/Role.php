<?php

namespace app\admin\model;

use think\Model;
use think\Session;

class Role extends Model
{
    //设置数据库表名
    //protected $table = 'admin';
    //设置主键
    protected $pk = 'id';

    // 设置当前模型的数据库连接
    public function getOne($data)
    {


        /*   @exec("arp -a",$array); //执行arp -a命令，结果放到数组$array中
         * foreach($array as $value){
         * //匹配结果放到数组$mac_array
         * if(strpos($value,$_SERVER["REMOTE_ADDR"]) && preg_match("/(:?[0-9A-F]{2}[:-]){5}[0-9A-F]{2}/i",$value,$mac_array)){
         * $mac = $mac_array[0];
         * break;
         * }
         * }
         * var_dump($mac);exit;*/
        $res = $this->where('username', $data['username'])->where('password', $data['password'])->find();

        return $res;

    }

    public function roleList($data)
    {
        //组装sql 查询数据 并组装成json
        $draw = $data['draw'];
        $order = $data['order'][0];
        $ordercolumn = $data['columns'][$order['column']]['data'];
        $ordertype = $order['dir'];
        $limitstart = $data['start'];
        $limitlength = $data['length'];

        $query = $data['search']['value'];
        $where[] = ['status', '<>', -1];
        if ($query != null) {
            $where[] = ['role_name|description', 'like', $query . "%"];
        }
        try {
            $alltotal = $this->where('status', '<>', -1)->count();
            $total = $this->where($where)->count();
            $res = $this->where($where)->order($ordercolumn, $ordertype)->limit($limitstart, $limitlength)->select()->toArray();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return ["draw" => $draw, "recordsTotal" => $alltotal, "recordsFiltered" => $total, "data" => $res];

    }

    public function conditionList($arr, $data = '')
    {
        $res = $this->where($arr)->select()->toArray();
        return $res;
    }

    public function getOneById($arr, $data = '')
    {
        $res = $this->where($arr)->find();
        return $res;
    }

    public function getMenuId($arr)
    {
        $res = $this->where($arr)->find()->toArray();
        return $res;
    }
}