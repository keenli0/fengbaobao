<?php

namespace app\admin\controller;

use think\Controller;
use think\facade\Session;
class Base extends Controller
{
    public function initialize()
    {
        $this->checkIsLogin();
        //$this->checkRole();
    }
    public function checkIsLogin()
    {
        if(!Session::get()){
            return $this->error('请重新登录', 'login/index');
        }
    }
/*    public function checkRole(){
        $menu_id = self::getRole();
        $flag = false;
        $allow = ['index/index','index/logout'];

        if ($menu_id=='admin'){

        }else{
            foreach ($menu_id as $k=>$v){
                print_r(strpos($_SERVER['REQUEST_URI'], url($v['url']))) ;
                if(strpos($_SERVER['REQUEST_URI'], url($v['url']))){
                    $flag = true;
                }

            }
            if ($flag == false){
                foreach ($allow as $k=>$v){
                    //echo url($v);
                    if(url($v)==$_SERVER['REQUEST_URI']){
                        $flag = true;
                    }

                }
            }
            if ($flag == false){
                return $this->error('没有权限');

            }
        }
    }*/
    public function getRole()
    {
        $user = Session::get('admin');
        //print_r($user);
        if ($user['username']=='admin'){
            return 'admin';
        }
        $role = Model('Role')->getMenuId([['id','=',$user['role_id']]]);
        $Data = Model('Menu')->all($role['menu_id'])->toArray();
        return $Data;
    }
}
