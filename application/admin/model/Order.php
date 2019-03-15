<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/9/18
 * Time: 15:05
 */

namespace app\admin\model;

use think\Model;

class Order extends Model
{
    protected $pk = 'id';

    //查询是否有重复订单
    public function findById($Id)
    {
        $res = $this->where('Id', $Id)->withAttr('ApplyTime', function($value, $data) {
            return date('Y-m-d- H:i:s', substr($value,0,10));
        })->find();
        return $res;
    }
    /** 格式化时间戳，精确到毫秒，x代表毫秒 */
    public function microtime_format($tag, $time)
    {
        print_r($time);
        list($usec, $sec) = explode(" ", $time);
        $date = date($tag,$usec);
        echo str_replace('x', $sec, $date);
    }
    //根据条件查询
    public function listByWhere($data, $where = '')
    {
        //组装sql 查询数据 并组装成json
        $draw = $data['draw'];
        $order = $data['order'][0];
        $ordercolumn = $data['columns'][$order['column']]['data'];
        $ordertype = $order['dir'];
        $limitstart = $data['start'];
        $limitlength = $data['length'];
        $query = $data['search']['value'];
        //是否有条件查询
        if ($where == '') {
            $where[] = ['status', '=', 1];
        }
        if ($query != null) {
            $where[] = ['Id|Name|Account|BankName|AgentAccount|MemberAccount', 'like', $query . "%"];
        }
        try {
            $alltotal = $this->where('status', '<>', -1)->count();
            $total = $this->where($where)->count();
            $res = $this->where($where)->order($ordercolumn, $ordertype)->limit($limitstart, $limitlength)->withAttr('ApplyTime', function($value, $data) {
                return date('Y-m-d- H:i:s', substr($value,0,10));
            })->select()->toArray();
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
        return ["draw" => $draw, "recordsTotal" => $alltotal, "recordsFiltered" => $total, "data" => $res];
    }
    public function getOneById($arr, $data = '')
    {
        $res = $this->where($arr)->find();
        return $res;
    }

    public function saveAisle($id,$data )
    {
        $res = $this->where($id)
            ->update($data);
        return $res;
    }

}