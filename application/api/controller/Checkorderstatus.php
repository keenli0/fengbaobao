<?php

namespace app\api\controller;

use think\Model;
use think\Controller;
use app\common\CacheLock;

class Checkorderstatus extends Controller
{
    private $format;
    private $act;
    private $otppwd;
    private $cookies_file;
    private $lock;
    private $url;

    protected function initialize()
    {
        $this->format = isset($_GET['format']) ? $_GET['format'] : 'html';
        $this->act = isset($_GET['act']) ? $_GET['act'] : '';
        $this->otppwd = isset($_GET['otppwd']) ? $_GET['otppwd'] : '';
        $this->cookies_file = \Env::get('app_path') . "common" . DIRECTORY_SEPARATOR . "cookies.txt";
        $this->lock = new CacheLock('AUTOSYNC', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lock');
        $this->url = getConfig('scan_url');
    }

    public function index()
    {
        return $this->fetch();
    }

    public function begin()
    {
        $queryurls = array();
        $where = ['is_query' => 1, 'is_auto_out_money' => 1, 'out_money_status' => 3, 'status' => 3];
        $orderlist = Model('Order')->getListBywhere($where);
        if ($orderlist != null) {

            foreach ($orderlist as $k => $v) {

                $aisleList = Model('Aisle')->get($v['payid'])->toArray();
                if ($aisleList != null) {

                    if ($aisleList['type'] == 'sufupay') {

                        $post['merchant_code'] = $aisleList['merchant_code'];
                        $post['now_date'] = date('Y-m-d H:i:s', time());
                        $post['trade_no'] = $v['trade_no'];
                        ksort($post);
                        $md5str = '';
                        foreach ($post as $key => $val) {
                            $md5str = $md5str . $key . "=" . $val . "&";
                        }
                        $post['sign'] = MD5($md5str . 'key=' . $aisleList['secretkey']);
                        ksort($post);
                        $response = curlPost($aisleList['query_url'], $post);
                        writelog("query_pay_status", $response);
                        $response = json_decode($response, true);
                        print_r($response);

                        if ($response['is_success'] == 'true' && isset($response['bank_status'])) {

                            if ($response['bank_status'] == 2) {

                                $id = ['auto_Id' => $v['auto_Id']];
                                $parame = ['out_money_status' => 1, 'notify_success' => 1,'notify_time'=>date('Y-m-d H:i:s',time())];
                                $res = Model('Order')->saveAisle($id, $parame);
                                if ($res != false) {

                                    $arr['id'] = $v['Id'];
                                    $rse = dataPost(getUrl('Allow'), $arr);
                                    writelog("query_pay_allow", $rse);

                                }
                            } elseif ($response['bank_status'] == 3) {

                                $id = ['auto_Id' => $v['auto_Id']];
                                $parame = ['out_money_status' => 2, 'notify_success' => 1,'notify_time'=>date('Y-m-d H:i:s',time())];
                                $res = Model('Order')->saveAisle($id, $parame);
                            } else {

                                $id = ['auto_Id' => $v['auto_Id']];
                                $parame = ['notify_count' => $v['notify_count'] += 1, 'notify_success' => 1];
                                $res = Model('Order')->saveAisle($id, $parame);

                            }
                            print_r($res);
                        } else {
                            print_r($response);
                            writelog("query_pay_status_error", $response);
                        }
                    }
                }
            }
        }
    }
}
