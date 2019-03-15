<?php

namespace app\admin\model;

use think\Model;
use think\Session;

class Menu extends Model
{
    //设置数据库表名
    //protected $table = 'admin';
    //设置主键
    protected $pk = 'id';

    // 设置当前模型的数据库连接

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->where('status', 1)->order('list_order', 'desc')->select();
    }

    /*
     * 菜单列表
     */
    public function menuList($data)
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
        if ($query!=null) {
            $where[] = ['name|class|icon|title|url','like',$query."%"];
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

    /**
     * 根据条件查询
     * @param $arr
     * @param string $data
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function conditionList($arr,$data=''){
        $res = $this->where($arr)->select();
        return $res;
    }
}