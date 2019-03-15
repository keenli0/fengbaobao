<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/9/18
 * Time: 15:05
 */

namespace app\checkgbk\model;

use think\Model;

class Order extends Model
{
    protected $pk = 'auto_id';

    /**
     * @param $where
     * @param $str
     * @return array
     * 根据查询条件返回查询结果
     */
    public function build_order()
    {
        $res = Db::query("select max(Id) from  tb_order");
        return $res;
    }

    //查询自动的最大值
    public function autoMaxId()
    {
        $res = $this->where('is_auto', 1)->order('Id', 'desc')->limit(0, 1)->value('Id');
        return $res;
    }

    //查询手动的
    public function unauto($orderMaxId)
    {
        $res = $this->where('Id', '>', $orderMaxId)->column('Id');
        return $res;
    }
}