<?php

namespace app\api\controller;

use think\Controller;
use think\Model;

class Index extends Controller
{
    public function index($id = 0)
    {
        $id = input('get.id');
        $arr = ['auto_Id'=>$id];
        $data = Model('Order')->getOneById($arr);
        $orderNo = date('YmdHis', time());
        $orderAmount = $data['Amount'];
        $this->assign('orderNo', $orderNo);
        $this->assign('orderAmount', $orderAmount);
        $this->assign('orderId', $id);
        $this->assign('url', 'url');

        return $this->fetch();
    }

}

