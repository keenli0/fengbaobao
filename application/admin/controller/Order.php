<?php
/**
 * Created by PhpStorm.
 * User: Owner
 * Date: 2018/9/18
 * Time: 14:54
 */

namespace app\admin\controller;

use think\Controller;
use app\common\CacheLock;
use think\Model;


class Order extends Base
{
    protected $model = null;

    //实例化
    public function initialize()
    {
        parent::initialize();
        $this->model = Model('Order');
    }

    //出款申请
    public function listList()
    {

        $data = input("post.");
        $res = $this->model->listList($data);
        return $res;
    }

    public function apply()
    {
        return $this->fetch();
    }

    //订单查询
    public function orderList()
    {

        $data = input("post.");
        $where[] = ['status', '=', 0];
        $res = $this->model->listByWhere($data, $where);
        return $res;
    }

    //审核中（风控）
    public function check()
    {
        return $this->fetch();
    }

    //失败订单
    public function errorOrderList()
    {

        $data = input("post.");
        $where[] = ['status', '=', 3];
        $where[] = ['out_money_status', '=', 2];
        $res = $this->model->listByWhere($data, $where);
        return $res;
    }

    public function fail_list()
    {
        return $this->fetch();
    }

    //成功订单
    public function successOrderList()
    {

        $data = input("post.");
        $where[] = ['status', '=', 3];
        $res = $this->model->listByWhere($data, $where);
        return $res;
    }

    public function succ_list()
    {
        return $this->fetch();
    }

    //出款订单
    public function ckOrderList()
    {

        $data = input("post.");
        $where[] = ['status', '=', 1];
        $res = $this->model->listByWhere($data, $where);
        return $res;
    }

    //待出款（财务）
    public function outmoney()
    {
        return $this->fetch();
    }

    //回退订单
    public function exitOrderList()
    {

        $data = input("post.");
        $where[] = ['status', '=', -1];
        $res = $this->model->listByWhere($data, $where);
        return $res;
    }

    public function exitindex()
    {
        return $this->fetch();
    }

    public function edit_status($data = '', $outsucc = '')
    {
        $res = 'false';
        if ($data == '') {
            $data = input("post.");
        }
        if (isset($data['Id'])) {
            if ($outsucc == '') {
                $exist = Model('Order')->findById($data['Id']);
                if ($exist['status'] == $data['status']) {
                    return ['msg' => '操作失败，该订单已经审核过', 'flag' => 0];
                } else {
                    if ($data['status'] == 1) {
                        $data['FinanceReview'] = date('Y-m-d H:i:s', time());
                    }
                    $res = $this->model->allowField(true)->where('Id', $data['Id'])->update($data);
                }
            } else {
                $res = $this->model->allowField(true)->where('Id', $data['Id'])->update($data);
            }

            if ($res === 'false') {
                return ['msg' => '操作失败', 'flag' => 0];
            } else {
                return ['msg' => '操作成功', 'flag' => 1];
            }
        } else {
            return ['msg' => '操作失败', 'flag' => 0];
        }

    }

    public function save()
    {
        $res = '';
        $data = input("post.");
        if (isset($data['id'])) {
            $res = $this->model->allowField(true)->update($data);//修改
        } else {
            $data['is_auto'] = 0;
            $exist = Model('CatchLists')->findById($data['Id']);
            if ($exist != null) {
                return ['msg' => '保存失败,该订单已存在', 'flag' => 0];
            } else {
                $res = $this->model->allowField(true)->save($data);//新增
            }
        }
        if (!$res && $res === 'false') {
            return ['msg' => '保存失败', 'flag' => 0];
        } else {
            return ['msg' => '保存成功', 'flag' => 1];
        }
    }

    public function add()
    {
        return $this->fetch();
    }

    //手动出款页面
    public function manual_out()
    {
        $data = input("post.data");
        $this->assign('list', $data);
        return $this->fetch();
    }

    //自动出款页面

    public function auto_out()
    {
        $data = Model('Aisle')->getAll();
        $this->assign('list', $data);
        return $this->fetch();
    }

    public function edit()
    {
        $data = input("post.");
        $list = $this->model->get($data['id']);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function order_info()
    {
        $data = input("post.");
        $list = $this->model->findById($data['id']);
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function black()
    {
        $data = input("post.data");
        $list = $this->model->get($data['Id']);
        $this->assign('list', $list);
        return $this->fetch();
    }
    public function turnDown()
    {
        $data = input("post.data");
        $this->assign('list', $data);
        return $this->fetch();
    }

    public function add_black()
    {
        $black_url = 'http://blacklist.test/index/index/add_black.html';
        $platform = 87;
        $data = input("post.");
        $exist = Model('Order')->findById($data['Id']);
        if ($exist) {
            $exist = $exist->toArray();
            $black['name'] = $exist['Name'];//会员姓名
            $black['member_account'] = $exist['MemberAccount'];//会员账号
            $black['bank_number'] = $exist['Account'];//银行卡号
            $black['platform'] = $platform;//银行卡号
            $black['token'] = 'a4sf+a*78fa*+sdf45as+d*asd+94as+d*7';//银行卡号
            $black['memo'] = $data['black_memo'];
            $res = curlPost($black_url, $black);
            $arr = json_decode($res, TRUE);
            if ($arr['code'] == 1) {
                $v['is_black'] = 1;
                $v['platform'] = $platform;
                $v['black_memo'] = $data['black_memo'];
                $flag = Model('Order')->where('Id', $data['Id'])->update($v);
            }
            return $arr;
        } else {
            return ['msg' => '加入黑名单失败,该数据不存在', 'flag' => 0];
        }
    }

    public function VerifyWithdraw()
    {
        $data = input("post.");
        $data = array_filter($data);
        $exist = Model('Order')->findById($data['id']);
        if (!isset($data['outsucc']) && $exist['status'] == $data['status']) {
            return ['msg' => '操作失败，该订单已经审核过', 'flag' => 0];
        }

        $res = null;
        if ($data['type'] == 'turndown') {
            if (isset($data['portalMemo'])) {
                $arr['id'] = $data['Id'];
                $arr['portalMemo'] = $data['portalMemo'];
                $res = self::dataPost(getUrl('UpdatePortalMemo'), $arr);
                unset($arr);
            }
            if (isset($data['memo'])) {
                $arr['id'] = $data['Id'];
                $arr['memo'] = $data['memo'];
                $test = self::dataPost(getUrl('UpdateMemo'), $arr);
                unset($arr);
            }
            $arr['id'] = $data['Id'];
            $rse = self::dataPost(getUrl('Deny'), $arr);
        } elseif ($data['type'] == 'outmoney') {
            if (isset($data['memo'])) {
                $arr['id'] = $data['Id'];
                $arr['memo'] = $data['memo'];
                $res = self::dataPost(getUrl('UpdateMemo'), $arr);
                unset($arr);
            }
            $arr['id'] = $data['Id'];
            $rse = self::dataPost(getUrl('Allow'), $arr);
        } else {
            return ['msg' => '错误', 'flag' => 0];
        }
        if ($rse == null) {
            return ['msg' => $rse['ErrorMessage'], 'flag' => 0];

        } else {
            $id = $data['Id'];
            $status = $data['status'];
            $parame = ['Id' => $id, 'status' => $status];
            if (isset($data['is_auto_out_money'])) {
                $parame['is_auto_out_money'] = $data['is_auto_out_money'];
            }
            //outsucc =1 如果是自动出款时间长未响应可以选择手动处理
            if (isset($data['outsucc'])) {
                $outsucc = 1;
                $parame['manual_processing_date'] = date('Y-m-d H:i:s', time());
                if ($status == -1) {
                    $parame['manual_processing'] = -1;

                } elseif ($status == 3) {
                    $parame['manual_processing'] = 1;
                }
                $edit_status = self::edit_status($parame, $outsucc);

            } else {
                $edit_status = self::edit_status($parame);
            }
            return $edit_status;
        }

    }

    public function dataPost($url, $data)
    {
        //print_r($url . "\r");
        $data = json_encode($data);
        //print_r($data);
        $lock = new CacheLock('AUTOSYNC', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lock');
        $cookies_file = \Env::get('app_path') . "common" . DIRECTORY_SEPARATOR . "cookies.txt";
        $headers = array('User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'); //发送自定义文件头
        doCurl($headers, $user = 'zdck', $pwd = '733060', $lock, $cookies_file, getConfig('scan_url'));
        $contents = doPost($headers, $url, $data, $cookies_file);
        $res = json_decode($contents, TRUE);
        //print_r($res);
        return $res;
    }
}