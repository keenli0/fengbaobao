<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/9/19
 * Time: 12:08
 */

namespace app\admin\model;


use app\admin\controller\Base;
use think\Model;

class Aisle extends Model
{
    protected $pk = 'id';

    public function payList($data)
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
        $where[] = ['status', '<>', -1];
        if ($query != null) {
            $where[] = ['type|name|agentnum|merchant-code|ip', 'like', $query . "%"];
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

    public function getAll($data = '')
    {
        $res = $this->select()->toArray();
        return $res;
    }

}