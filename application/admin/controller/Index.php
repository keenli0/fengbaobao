<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use think\facade\Session;
use app\admin\controller\Base;
use app\admin\model\Menu;
use app\admin\controller\menu\BuildMenuHtml;

class Index extends Base
{
    /**
     * Admin模型对象
     */
    protected $model = null;

    public function initialize()
    {
        parent::initialize();
        $this->model = new Admin();
    }

    public function index()
    {
        $menu = new Menu();
        $menu_id = self::getRole();
        //print_r($menu_id['menu_id']);
        if ($menu_id == 'admin') {
            $Data = $menu->getAll()->toArray();
        } else {
            $Data = $menu_id;
            //print_r($Data);exit;
        }
        //$Data = $menu ->getAll()->toArray();
        foreach ($Data as $value) {
            $arr = array("id" => $value['id'], "pid" => $value['pid'], "url" => url($value["url"], $value['param']), "title" => $value['title'], 'class' => $value['class'], "name" => $value['name'], "icon" => $value['icon']);
            //print_r($arr);
            $back_data_array[$value['pid']][] = $arr;//pid0的会放到第一个0下标的数组，所以下标0的里面都是顶级菜单
            unset($value);
        };
        $menu_html = new BuildMenuHtml($back_data_array);
        $html = $menu_html->buildMenu();
        $this->assign("menuhtml", $html);
        $user = Session::get('admin');
        $this->assign("admin", $user['name']);

        return $this->fetch();
    }

    public function desktop()
    {

        return $this->fetch();
    }

    public function login()
    {
        Session::delete('admin');
        return $this->success('退出成功', url('login/index'));
    }


}
