<?php

namespace app\checkgpk\controller;

use think\Controller;
use think\facade\Session;

class Login extends controller
{
    /**
     * Admin模型对象
     */
    protected $model = null;
    //实例化对象
    public function initialize()
    {
        parent::initialize();
        $this->model = new Admin();
    }

    //
    public function index()
    {
        return $this->fetch('index/login');
    }
    /*public function checkLogin()
    {
        $parame = input('post.');
        if($parame['username'] == '123456'&&$parame['password'] == '123456'){
            return $this->success('登陆成功','index/index');
        }else{
            return $this->error('登陆失败','index/login');

        }
    $vegetables[0] = "corn";
$vegetables[1] = "broccoli";
$vegetables[2] = "zucchini";
$text = implode(",", $vegetables);
echo $text;


foreach（$arr as $val）{
    $new[] = $val['dept'];
}
$data['dept'] = implode('、',$new);
print_r($data);

    */
    public function checkLogin()
    {
        $data = input('post.');
        $res = $this->model->getOne($data);
        $snames = Session::set('sname', $res);
        $sname = session::get($snames);
        //随机数后六位

        /*   $sjs=var_dump(string_make_guid());
           var_dump(substr(string_make_guid(),26,7));exit();
           //md5加密转大写再加密 加随机数 再加密
           echo strtoupper(md5($data['password']));
           exit();*/

        if ($res) {
            return $this ->redirect('index/index');
            //return $this->success('登陆成功', 'index/index');
        } else {
            $this->error('用户名或密码错误');

        }
    }
}
