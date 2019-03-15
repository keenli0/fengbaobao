<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/9/19
 * Time: 12:07
 */

namespace app\admin\controller;

class Aisle extends Base
{

    protected $model = null;
    protected $autoWriteTimestamp = true;

    public function initialize()
    {
        parent::initialize();
        $this->model = Model('Aisle');
    }

    public function index()
    {
        return $this->fetch();
    }

    public function payList()
    {
        $data = input("post.");
        $res = $this->model->payList($data);
        return $res;
    }

    public function edit_status()
    {
        $data = input("post.");
        if (isset($data['id'])) {
            $res = $this->model->allowField(true)->update($data);
        }
        json_encode($res->toArray());
        if ($res === 'false') {
            return ['msg' => '操作失败', 'flag' => 0];
        } else {
            return ['msg' => '操作成功', 'flag' => 1];
        }
    }

    //编辑用户
    public function edit()
    {
        $data = input("post.");
        $list = $this->model->get($data['id']);
        $this->assign('list', $list);
        return $this->fetch();
    }

    //保存
    public function save()
    {
        $data = input("post.");
        if (isset($data['id'])) {
            $res = $this->model->allowField(true)->update($data);
        } else {
            $res = $this->model->allowField(true)->save($data);
        }
        if ($res === 'false') {
            return ['msg' => '保存失败', 'flag' => 0];
        } else {
            return ['msg' => '保存成功', 'flag' => 1];
        }
    }

    public function add()
    {
        /*        $data = input("post.");
                $list=$this->model->conditionList($data)->toArray();
                $this->assign('list',$list);*/
        return $this->fetch();
    }

    public function getAisle()
    {
        $aisleId = input("post.aisleId");
        $orderId = input("post.orderId");
        if (!$aisleId) {
            return ['msg' => '没有选择', 'flag' => 0, 'res' => null];
        } else {
            $aislelist = $this->model->get($aisleId);
            if (!$aislelist) {
                return ['msg' => '没有该通道', 'flag' => 0, 'res' => null];

            }
        }
        if (!$orderId) {
            return ['msg' => '订单参数错误', 'flag' => 0, 'res' => null];
        } else {
            $arr = ['auto_Id' => $orderId, 'status' => 1];
            $orderlist = Model('Order')->getOneById($arr);
            if (!$orderlist) {
                return ['msg' => '没有该订单或该订单已经处理过', 'flag' => 0, 'res' => null];
            } else {
                $id = ['auto_Id' => $orderId];
                $arr = ['payid' => $aisleId, 'payname' => $aislelist['name']];
                $res = Model('Order')->saveAisle($id, $arr);
                if ($res === false) {
                    return ['msg' => '选择通道失败', 'flag' => 0, 'res' => null];
                } else {
                    return ['msg' => 'ok', 'flag' => 1, 'res' => null];
                }
            }
        }
    }

    public function payment()
    {
        $aisleId = input("post.aisleId");
        $orderId = input("post.orderId");

        if (!$aisleId) {
            return ['msg' => '没有选择', 'flag' => 0, 'res' => null];
        } else {
            $aislelist = $this->model->get($aisleId);
            if (!$aislelist) {
                return ['msg' => '没有该通道', 'flag' => 0, 'res' => null];
            }else{
                $url = '//'.$_SERVER['SERVER_NAME'].'/api/'.'index'.'/?id='.$orderId;
                //print_r($url);
                $this->assign('url',$url);
            }
        }
        return $this->fetch();
    }

}