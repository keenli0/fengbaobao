<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin;
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

    public function index()
    {
        return $this->fetch('index/login');
    }

    public function checkLogin()
    {
        $data = input('post.');
        if ($data['username'] == '' || $data['password'] == '') {
            $this->error('用户名或密码不能为空');
        } else {
            $data['password'] = md5(strtoupper(md5($data['password'])));
            $res = $this->model->getOne($data);
            if ($res) {
                $res->toArray();
                Session::set('admin', $res);
                return $this->redirect('index/index');
                //return $this->success('登陆成功', 'index/index');
            } else {
                $this->error('用户名或密码错误');
            }
        }

    }

    public function lang()
    {
        switch ($_GET['lang']) {
            case 'zh';
                cookie('think_var', 'zh-cn');
                break;
            case 'en';
                cookie('think_var', 'en-us');
                break;
        }
    }
}
