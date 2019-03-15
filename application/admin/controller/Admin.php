<?php

namespace app\admin\controller;
class Admin extends Base
{

    /**
     * Admin模型对象
     */
    protected $model = null;

    //实例化对象
    public function initialize()
    {
        parent::initialize();
        $this->model = Model('Admin');
    }
    //跳转的页面
    public function index()
    {
        return $this->fetch();
    }
    //用户查询
    public function userList()
    {
        $data = input("post.");
        $res = $this->model->userList($data);
        return $res;
    }
    //编辑用户
    public function edit()
    {
        $data = input("post.");
        $list=$this->model->get($data['id']);
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function save()
    {
        $data = input("post.");
        if (isset($data['password'])){
            $data['password'] = md5(strtoupper(md5($data['password'])));
        }
        if (isset($data['id'])){
            $res = $this->model->allowField(true)->update($data);
        }else{
            $res = $this->model->allowField(true)->save($data);
        }
        if ($res === 'false'){
            return ['msg'=>'保存失败','flag'=>0];
        }else{
            return ['msg'=>'保存成功','flag'=>1];
        }
    }

    //添加回填页面
    public function add()
    {
        $where=['status'=>'1'];
        $res = Model('Role')->conditionList($where);
        $this->assign('list',$res);
        return $this->fetch();
    }

    /**
     * 用户状态修改 停用，启用，删除
     * @return array
     */
    public function edit_status()
    {
        $data = input("post.");
        if (isset($data['id'])){
            $res = $this->model->allowField(true)->update($data);
        }
        if ($res === 'false'){
            return ['msg'=>'操作失败','flag'=>0];
        }else{
            return ['msg'=>'操作成功','flag'=>1];
        }
    }
    //用户角色
    public function role()
    {
        return $this->fetch();
    }
    public function roleList()
    {
        $data = input("post.");
        $res = Model('Role')->roleList($data);
        return $res;
    }
    //添加角色
    public function roleadd()
    {
        //先查询菜单
        $list = Model('Menu')->conditionList([['status','<>','-1']]);
        $this->assign('list',$list);
        return $this->fetch();
    }

}
