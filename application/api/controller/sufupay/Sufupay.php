<?php

namespace app\api\controller\sufupay;

use think\Controller;

class Sufupay extends Controller
{
    public function index()
    {
        header("Content-type: text/html; charset=utf-8");

        $orderNo = rand(10000000, 99999999);
        $orderAmount = round((rand() / getrandmax()) * 10000) / 100;
        $returnParams = "0|EF9012AB21";
        $this->assign('orderNo',$orderNo);
        $this->assign('orderAmount',$orderAmount);
        $this->assign('orderAmount',$orderAmount);

        return $this->fetch();
    }

}

