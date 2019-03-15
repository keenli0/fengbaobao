<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function string_make_guid()
{
    // 1、去掉中间的“-”，长度有36变为32
    // 2、字母由“大写”改为“小写”
    if (function_exists('com_create_guid') === true) {
        return strtolower(str_replace('-', '', trim(com_create_guid(), '{}')));
    }

    return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

// 获取客户端IP
function get_client_ip()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }

    return $ip;
}

//写日志
function WriteSyncLog($content)
{
    if (!$content) {
        return false;
    }

    $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;

    if (!is_dir($dir)) {
        if (!mkdir($dir)) {
            return false;
        }
    }
    $filename = $dir . DIRECTORY_SEPARATOR . date("Ymd", time()) . '.log.php';

    $str = "<?php exit();?>\n[" . date('Y-m-d H:i:s') . "] ";
    if (is_string($content)) {
        $str .= $content;
    } else {
        return false;
    }
    $str .= "\n--------------------------------------------------------------------------------------------------\n";
    if (!$fp = @fopen($filename, "a+")) {
        return false;
    }
    if (!fwrite($fp, $str)) {
        return false;
    }
    fclose($fp);
    return true;
}

/**
 * 日志记录，按照"Ymd.log"生成当天日志文件
 * 日志路径为：入口文件所在目录/logs/$type/当天日期.log.php，例如 /logs/error/20120105.log.php
 * @param string $type 日志类型，对应logs目录下的子文件夹名
 * @param string $content 日志内容
 * @return bool true/false 写入成功则返回true
 */
function writelog($type, $content)
{
    if (!$type || !$content) {
        return false;
    }
    $dir = \Env::get('ROOT_PATH') . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . $type;

    if (!is_dir($dir)) {
        if (!mkdir($dir)) {
            return false;
        }
    }
    $filename = $dir . DIRECTORY_SEPARATOR . date("Ymd", time()) . '.log.php';

    $str = "<?php exit();?>\n写入时间:" . date('Y-m-d H:i:s') . "\n";
    $str .= "提交IP:" . get_client_ip() . "\n";
    if (is_array($content)) {
        $str .= "提交方法:" . $_SERVER['REQUEST_METHOD'] . "\n数据内容:\n";
        $param = '';
        foreach ($content as $key => $val) {
            $param .= empty($param) ? '' : '&';
            $param .= $key . '=' . $val;
        }
        $str .= $param;
    } elseif (is_string($content)) {
        $str .= $content;
    } else {
        return false;
    }
    $str .= "\n=================================================================================================================\n";
    if (!$fp = @fopen($filename, "a+")) {
        return false;
    }
    if (!fwrite($fp, $str)) {
        return false;
    }
    fclose($fp);
    return true;
}

function output($str, $type)
{
    if ($type == 'json') {
        echo json_encode(array('status' => 1, 'msg' => $str));
        exit();
    } else {
        die($str);
    }
}

//获取配置文件
function getConfig($data)
{
    return db('Config')->where("key", $data)->value('value');
}

/**
 * @param $headers
 * @param $user
 * @param $pwd
 * @param $lock
 * @param $cookies_file
 * @param $url
 * 检测登录状态并登录
 */
function doCurl($headers, $user, $pwd, $lock, $cookies_file, $url)
{
    //先访页面问看是否有掉线 302重定向到登录页面就是cookie失效
    //测试cookie是否可用，不可用再登录
    $ch = curl_init(); //初始化
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_ENCODING, "utf-8");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 2); //允许重定向次数
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //优先解析ip4
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $lock->lock();

    $canUse = false;
    curl_setopt($ch, CURLOPT_URL, $url . 'Home/GetAuthorities');
    curl_setopt($ch, CURLOPT_HEADER, true); //返回header部分
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies_file); //携带cookies
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);//不返回body部分
    $response = curl_exec($ch);
    curl_close($ch);

    //print_r($response);exit;
    if (strpos($response, 'HTTP/1.1 302') !== false) {
        WriteSyncLog("COOKIES不可用,启动登录步骤……");
    } else if (strpos($response, 'HTTP/1.1 403') !== false) {
        WriteSyncLog("没有访问权限，请联系平台……");
    } else {
        $canUse = true;
        WriteSyncLog("COOKIES可用,跳过登录步骤……");
    }

//cookies不可用，重新登录
    if (!$canUse) {
        WriteSyncLog("开始登录");
        //登录
        $param = array();
        $param['account'] = $user;
        $param['password'] = $pwd;
        $ch = curl_init(); //初始化

        curl_setopt($ch, CURLOPT_URL, $url . 'Account/ValidateAccount');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE); //不返回header部分
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies_file); //存储cookies
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($response, TRUE);
        //$validate = $otppwd; //验证码
        //var_dump('200的话就不会走这一步！！！！！！！！'.$res);
        if ($res['IsSuccess'] === true) {
            WriteSyncLog("登录后台成功！");

        } else {
            WriteSyncLog("登录失败！原因:{$res['ErrorMessage']}");
            output('[' . date('H:i:s') . "] 登录失败！原因:{$res['ErrorMessage']}！", $this->format);
        }
    }
}

/**
 * @param $lock
 * @param $is_auto
 * 检测是否开启自动同步
 */

function isset_auto($lock, $is_auto)
{
    WriteSyncLog(date('Y-m-d H:i:s') . " 开始同步任务");
    if ($is_auto == 0) {
        $lock->unlock();
        WriteSyncLog(date('Y-m-d H:i:s') . " 未开启自动同步");
        output('[' . date('H:i:s') . "] 未开启自动同步", $this->format);
    }
}

function doPost($headers, $url, $data, $cookies_file)
{
    $headers[] = 'Content-Type: application/json;charset=utf-8';
    $headers[] = 'Content-Length:' . strlen($data);
    $headers[] = 'X-Requested-With:XMLHttpRequest';
    $ch = curl_init(); //初始化
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//允许重定向次数
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, false); //返回头部
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500); //timeout on connect
    curl_setopt($ch, CURLOPT_TIMEOUT, 500); //timeout on response
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies_file); //使用上面获取的cookies
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($ch, CURLOPT_ENCODING, "big5");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 2); //允许重定向次数
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //优先解析ip4
    $contents = curl_exec($ch);
    @curl_close($ch);
    return $contents;
}

/**
 * @param $param
 */
function getUrl($param)
{
    switch ($param) {
        case 'Deny':
            return getConfig('scan_url') . 'VerifyWithdraw/Deny';
            break;
        case 'Allow':
            return getConfig('scan_url') . 'VerifyWithdraw/Allow';
            break;
        case 'UpdateMemo':
            return getConfig('scan_url') . 'VerifyWithdraw/UpdateMemo';
            break;
        case 'UpdatePortalMemo':
            return getConfig('scan_url') . 'VerifyWithdraw/UpdatePortalMemo';
            break;
    }
}

//第三方post
function curlPost($url, $data = '')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSLVERSION, 1);//这个值不设置有时会出现error 35情况
    return curl_exec($ch);
}

//提交post
function dataPost($url, $data)
{
    $data = json_encode($data);
    $lock = new app\common\CacheLock('AUTOSYNC', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lock');
    $cookies_file = \Env::get('app_path') . "common" . DIRECTORY_SEPARATOR . "cookies.txt";
    $headers = array('User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36'); //发送自定义文件头
    doCurl($headers, $user = 'zdck', $pwd = '733060', $lock, $cookies_file, getConfig('scan_url'));
    $contents = doPost($headers, $url, $data, $cookies_file);
    $res = json_decode($contents, TRUE);
    //print_r($res);
    return $res;
}


//url分批 5个一批

function url_batch($urls)
{

    //$urls=count_line();

    $branch_url = array();

    $p = 5;//每次执行多少条

    $ring = ceil(count($urls) / $p);

    for ($n = 0; $n < $ring; $n++) {
        $temp_url = array();

        $star = $n * $p;

        $end = ($n + 1) * $p;

        for ($ii = $star; $ii < $end; $ii++) {

            if (isset($urls[$ii])) {

                $temp_url[] = $urls[$ii];

            }

        }

        curl_multi($temp_url);

        // array_push($branch_url, $temp_url);
    }
    return $branch_url;
}

function curl_multi($urls)
{
    $mh = curl_multi_init();
    foreach ($urls as $i => $url) {
        // 创建一对cURL资源
        $conn[$i] = curl_init();
        // 设置URL和相应的选项
        curl_setopt($conn[$i], CURLOPT_URL, $url);
        curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn[$i], CURLOPT_HEADER, 0);
        curl_setopt($conn[$i], CURLINFO_HEADER_OUT, true); //TRUE 时追踪句柄的请求字符串，从 PHP 5.1.3 开始可用。这个很关键，就是允许你查看请求header
        curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, false); //
        curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($conn[$i], CURLOPT_TIMEOUT, 0);   //只需要设置一个秒的数量就可以
        // 增加句柄
        curl_multi_add_handle($mh, $conn[$i]);//var_dump($conn[$i]);
    }
    //开始执行
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);
    if (curl_errno($mh)) {
        echo 'Curl error: ' . curl_error($mh);
    }
    foreach ($urls as $i => $url) {

        $data = curl_multi_getcontent($conn[$i]);
        curl_multi_remove_handle($mh, $conn[$i]);
        curl_close($conn[$i]);
        $match = array();
        $wait = array();
        preg_match_all("(<title>(.*)</title>)", $data, $match);
        /*var_dump($match[1][0]);*/
        //if(isset($match[1][0])){
        var_dump("title:" . $url . '------>find ok!!!');
        $time = date('y.m.d');
        $fileName = $time . '.txt';
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . 'newdomain' . DIRECTORY_SEPARATOR . $fileName;
        $myfile = fopen($fileName, "a") or die("Unable to open file!");
        if ($match[1][0] != null) {
            fwrite($myfile, $url . "|" . $match[1][0] . "\r");
        } else {
            fwrite($myfile, $url . "|" . '【没有获取到Title】' . "\r");
        }

        fclose($myfile);
        //}

    }
    curl_multi_close($mh);
}
