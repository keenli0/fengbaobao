<?php

namespace app\admin\controller;
use think\Controller;


class Menu extends Base
{
    /**
     * Menu模型对象
     */
    protected $model = null;

    public function initialize()
    {
        parent::initialize();
        $this->model = Model('Menu');
    }

    /**
     * 主页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }
    /**
     * 编辑页
     * @return mixed
     */
    public function edit()
    {
        $data = input("post.");
        $list=$this->model->get($data['id']);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 新增页
     * @return mixed
     */
    public function add()
    {
        $data = input("post.");
        $list=$this->model->conditionList([['is_menu','=',1],['status','=',1],['pid','in','-1,0']])->toArray();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 保存
     * @return array
     */
    public function save()
    {
        $data = input("post.");
        $validate = validate('Menu');
        $validate->check($data);
        if(!$validate->check($data)) {
            $this->error($validate->getError());
        }
        if (isset($data['id'])){
            $res = $this->model->allowField(true)->update($data);
        }else{
            $res = $this->model->allowField(true)->save($data);
        }

        if ($res === 'false'){
            return ['code'=>0,'msg'=>'保存失败',"data"=>"","url"=>"","wait"=>''];
        }else{
            return ['code'=>1,'msg'=>'保存成功',"data"=>"","url"=>"","wait"=>''];
        }
    }
    /**
     * 删除
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
    /**
     * 菜单列表
     * @return mixed
     */
    public function menuList()
    {
        $data = input("post.");
        //print_r($data);
        $res = $this->model->menuList($data);
        return $res;
    }
}
