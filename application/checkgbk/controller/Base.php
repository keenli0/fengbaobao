<?php

namespace app\checkgpk\controller;

use think\Controller;
use think\facade\Session;
class Base extends Controller
{
    public function initialize()
    {
        $this->checkIsLogin();
    }
    public function checkIsLogin()
    {
        if(!Session::get()){
            return $this->error('请重新登录', 'login/index');
        }
    }
}
