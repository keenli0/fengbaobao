<?php

namespace app\checkgbk\controller;
set_time_limit(0);

use think\Model;
use think\Controller;
use app\common\CacheLock;

class Catchlists extends Controller
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
        $lock = $this->lock;
        $cookies_file = $this->cookies_file;
        $url = $this->url;
        $is_auto = getConfig('is_auto');
        $headers = array('User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'); //发送自定义文件头
        doCurl($headers, $user = 'zdck', $pwd = '733060', $lock, $cookies_file, $url);
        isset_auto($lock, $is_auto);
        self::start($headers, $cookies_file, $url);
        self::getInfo($headers, $cookies_file, $url);
        self::build_order();

        $this->lock->unlock();

    }

    /**
     * @param $headers
     * 开始获取数据
     */
    public function start($headers, $cookies_file, $url)
    {
        $data = '{"count":25,"minId":null,"query":{}}';
        WriteSyncLog("开始同步数据……");
        $syncSuccess = 0;
        $url = $url . 'VerifyWithdraw/Load';
        $contents = doPost($headers, $url, $data, $cookies_file);
        WriteSyncLog("获取到的数据" . $contents);
        $arr = json_decode($contents, TRUE);
        $data = $arr['Data'];
        //print_r($arr);

        //print_r($data);
        if ($arr['IsSuccess'] === false) {
            $result = "获取失败,原因:" . $arr['ErrorMessage'];
        } else {
            $result = "获取成功";
            $syncSuccess = 1;
            //Model('CatchLists')->replace()->saveAll($data);
            $maxId = Model('CatchLists')->maxid();
            //print_r('数据库最大ID' . $maxId . "\r");
            $list = array();
            foreach ($data as $k => $v) {
                if ($v['Id'] > $maxId) {
                    $ApplyTime = '';
                    $flag = preg_match_all('/\d+/', $v['ApplyTime'], $ApplyTime);
                    $v['ApplyTime'] = $ApplyTime[0][0];
                    //echo $v['ApplyTime'];
                    $list[] = $v;
                }
            }
            asort($list);
            $res = Model('CatchLists')->allowField(true)->replace()->saveAll($list);
            //print_r($list);
        }

        //$this->lock->unlock();
        //output('[' . date('H:i:s') . "] 同步任务结束", $this->format);

    }


    /**
     * @param $headers
     * @param $cookies_file
     * @param $url
     * 开始处理数据
     */
    public function getInfo($headers, $cookies_file, $url)
    {
        $maxId = Model('BuildOrder')->maxid();
        $idArr = Model('CatchLists')->where('Id', '>', $maxId)->where('State', 'Apply')->where('is_check', '0')->where('is_build', '0')->column('Id');
        //print_r($idArr);
        WriteSyncLog("开始同步数据……");
        $syncSuccess = 0;
        //print_r(1213213123123);
        $list = array();
        $updataArr = array();
        asort($idArr);
        foreach ($idArr as $k => $v) {
            $data = '{"id":' . $v . '}';
            $contents = doPost($headers, $url . 'VerifyWithdraw/GetDetail', $data, $cookies_file);
            WriteSyncLog("获取到的数据" . $contents);
            $arr = json_decode($contents, TRUE);
            $ApplyTime = '';
            $flag = preg_match_all('/\d+/', $arr['Detail']['ApplyTime'], $ApplyTime);
            $arr['Detail']['ApplyTime'] = $ApplyTime[0][0];
            $list[] = $arr['Detail'];
            $updataArr[] = ['Id' => $v, 'is_check' => '1'];
            //print_r($list);
        }
        /*print_r($list);
        print_r($updataArr);*/
        Model('BuildOrder')->allowField(true)->replace()->saveAll($list);
        Model('CatchLists')->isCheck($idArr);

        //$this->lock->unlock();
        output('[' . date('H:i:s') . "] 同步任务结束", 'json');

    }

    /**
     * @return string
     * 创建订单并查询黑名单
     */
    public function build_order()
    {
        $url = 'http://blacklist.test/index/index/black_list.html';
        $orderMaxId = Model('Order')->autoMaxId();
        if ($orderMaxId == null) {
            $orderMaxId = 0;
        }
        $unautoId = Model('Order')->unauto($orderMaxId);
        if ($unautoId == null) {
            $unautoId = 0;
        }
        $res = Model('BuildOrder')->where('Id', '>', $orderMaxId)->where('Id', 'not in', $unautoId)->where('is_build', 0)->select()->toArray();
        foreach ($res as $k => $v) {
            unset($v['auto_Id']);
            $data['name'] = $v['Name'];
            $data['member_account'] = $v['MemberAccount'];
            $data['bank_number'] = $v['Account'];

            $blacklist = curlPost($url, $data);//查询黑名单
            $arr = json_decode($blacklist, TRUE);
            print_r($arr);
            if (is_array($arr) && isset($arr['code'])) {
                print_r(111);
                if ($arr['code'] == 1) {
                    $v['is_black'] = 1;
                    $v['platform'] = $arr['platform'];
                    $v['black_memo'] = $arr['memo'];
                }
            }
            $flag = Model('Order')->allowField(true)->save($v);
            if ($flag != false) {
                $flag1 = Model('BuildOrder')->where('Id', $v['Id'])->update(['is_build' => '1']);
                $flag2 = Model('Catchlists')->where('Id', $v['Id'])->update(['is_build' => '1']);
            } else {
                return '失败';
            }
        }
        output('[' . date('H:i:s') . "] 已创建订单", 'json');

    }
}
