<?php

namespace app\checkgbk\controller;

use think\Controller;
use think\Db;
use app\common\CacheLock;

class Buildorder extends Controller
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
        self::build_order();
    }

    /**
     * @param $headers
     * @param $cookies_file
     * @param $url
     * 开始处理数据
     */
    public function start($headers, $cookies_file, $url)
    {
        $maxId = Model('BuildOrder')->maxid();
        $idArr = Model('CatchLists')->where('Id', '>', $maxId)->where('State', 'Apply')->where('is_check', '0')->where('is_build', '0')->column('Id');
        //print_r($idArr);
        WriteSyncLog("开始同步数据……");
        $syncSuccess = 0;

        $list = array();
        $updataArr = array();
        asort($idArr);
        foreach ($idArr as $k => $v) {
            $data = '{"id":' . $v . '}';
            print_r($data);
            $contents = doPost($headers,$url . 'VerifyWithdraw/GetDetail', $data,$cookies_file);
            print_r($contents);
            WriteSyncLog("获取到的数据" . $contents);
            $arr = json_decode($contents, TRUE);
            $list[] = $arr['Detail'];
            $updataArr[] = ['Id' => $v, 'is_check' => '1'];
            print_r($list);
        }
        /*print_r($list);
        print_r($updataArr);*/
        Model('BuildOrder')->allowField(true)->replace()->saveAll($list);
        Model('CatchLists')->isCheck($idArr);

        $this->lock->unlock();
        //output('[' . date('H:i:s') . "] 同步任务结束", $this->format);

    }

    public function build_order()
    {
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
            $flag = Model('Order')->allowField(true)->save($v);
            if ($flag != false) {
                $flag1 = Model('BuildOrder')->where('Id', $v['Id'])->update(['is_build' => '1']);
                $flag2 = Model('Catchlists')->where('Id', $v['Id'])->update(['is_build' => '1']);
            } else {
                return '失败';
            }
        }
    }
}
