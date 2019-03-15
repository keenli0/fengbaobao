<?php

namespace app\api\controller\sufupay;

use think\Controller;

use app\api\controller\sufupay\AppConstants;


require("constant.php");

class Paysubmit extends Controller
{
    protected $bankCode;
    protected $orderNo;
    protected $orderAmount;
    protected $referer;
    protected $customerIp;
    protected $returnParams;
    protected $currentDate;
    protected $model;

    public function initialize()
    {
        //$this->bankCode = $_POST[AppConstants::$BANK_CODE];
        $this->orderNo = $_POST['trade_no'];
        //$this->orderAmount = $_POST[AppConstants::$ORDER_AMOUNT];
        $this->referer = REQ_REFERER;
        $this->customerIp = getClientIp();
        //$this->returnParams = $_POST[AppConstants::$RETURN_PARAMS];
        $this->currentDate = date(DATE_TIME_FORMAT, time());
    }
    public function bank_code($param)
    {
        switch ($param) {
            case '农业银行':
                return 'ABC';
                break;
            case '中国银行':
                return 'BOC';
                break;
            case '交通银行':
                return 'BOCOM';
                break;
            case '建设银行':
                return 'CCB';
                break;
            case '工商银行':
                return 'ICBC';
                break;
            case '邮政储蓄银行':
                return 'PSBC';
                break;
            case '招商银行':
                return 'CMBC';
                break;
            case '浦发银行':
                return 'SPDB';
                break;
            case '光大银行':
                return 'CEBBANK';
                break;
            case '中信银行':
                return 'ECITIC';
                break;
            case '平安银行':
                return 'PINGAN';
                break;
            case '民生银行':
                return 'CMBCS';
                break;
            case '华夏银行':
                return 'HXB';
                break;
            case '广发银行':
                return 'CGB';
                break;
            case '北京银行':
                return 'BJBANK';
                break;
            case '上海银行':
                return 'BOS';
                break;
            case '兴业银行':
                return 'CIB';
                break;
        }
    }
    public function setData()
    {
        $orderId = input("post.orderId");
        $arr = ['auto_Id' => $orderId];
        $orderlist = Model('Order')->getOneById($arr);
        if (!$orderlist && $orderlist['status'] != 1 && $orderlist['status'] != 1 && $orderlist['outmoney_time'] != null && $orderlist['out_money_status'] != 0 && $orderlist['bankcode'] != null) {
            echo '订单状态异常';
            die();
        }
        $aislelist = Model('Aisle')->get($orderlist['payid']);
        if (!$aislelist && $orderlist['status'] != 1) {
            echo '支付接口不可用';
            die();
        }
        $data['account_name'] = $orderlist['Name'];
        $data['account_number'] = $orderlist['Account'];
        //$data['bank_code'] = $this->bankCode;
        $data['bank_code'] = self::bank_code($orderlist['BankName']);

        $data['merchant_code'] = $aislelist['merchant_code'];
        $data['order_time'] = $this->currentDate;
        $data['trade_no'] = $orderlist['MemberAccount'] . $this->orderNo;
        $data['order_amount'] = $orderlist['Amount'];
        ksort($data);
        $md5str = '';
        foreach ($data as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $data['sign'] = MD5($md5str . 'key=' . $aislelist['secretkey']);
        $parameter = '';
        foreach ($data as $key => $val) {
            $parameter = $parameter . $key . "=" . $val . "&";
        }
        $res = curlPost(GATEWAY_URL, $parameter);
        //echo $res;
        writelog("sufupay_pay", $res);
        $res = json_decode($res, true);
        if (is_array($res) && isset($res['is_success']) != false) {
            $id = ['auto_id' => $orderId];
            $par = ['out_money_status' => 3, 'outmoney_time' => $this->currentDate,'com_time'=>date('Y-m-d H:i:s',time()) ,'status' => 3, 'is_query' => 1, 'bankcode' =>self::bank_code($orderlist['BankName']), 'trade_no' => $orderlist['MemberAccount'] . $this->orderNo];
            $updateRes = Model('Order')->saveAisle($id, $par);
            if ($updateRes != false) {
                echo '操作成功！';
            }
        }else{
            writelog("sufupay_pay", $res);
            echo '操作失败！';
            echo $res['errror_msg'];
        }
    }


}