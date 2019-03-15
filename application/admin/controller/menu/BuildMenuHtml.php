<?php
namespace app\admin\controller\menu;
class BuildMenuHtml
{
    protected $data_array;
    public function __construct($data){
        $this->data_array = $data;
        //初始化
    }
    //设置数据
    public function setData($data){
        $this->data_array = $data;
    }
    //创建菜单
    public function buildMenu(){
        //迭代创建
        return  self::iteration($this->data_array, 0);
    }
    //迭代菜单循环  这个注释先不要删除
/*    protected  function iteration($data_array, $id, $tag = ''){
        $int_count = count($data_array[$id]);//先计算当前数组有几个下标为0的，就是几个顶级菜单，下面遍历用到
        $newsarrays = $data_array[$id];//把顶级节点放到新数组
        for($i = 0; $i < $int_count; $i++){
            $tags ='';
            if(@$data_array[''.$newsarrays[$i][0]] == null){
                $tags = self::create_menu_tag($newsarrays[$i],$g = '',true);
            }
            else {
                $tags = self::iteration($data_array, ''.$newsarrays[$i][0], '' );
                $tags = self::create_menu_tag($newsarrays[$i],$tags,false);
            }

            $tag = $tag . $tags;
        }
        return $tag;
    }*/
    //迭代菜单循环
    protected  function iteration($allmenus, $pid, $tag = ''){
        $top_count = count($allmenus[$pid]);//先计算当前数组有几个下标为0的，就是几个顶级菜单，下面遍历用到
        $nodearrays = $allmenus[$pid];//把顶级节点放到新数组 $newsarrays[$i][0]是自己的id $data_array[$newsarrays[$i][0]]
        //print_r($nodearrays);
/*        for($i = 0; $i < $top_count; $i++){ //遍历当前菜单组中的所有单个菜单
            $tags ='';
            $menus_array = $nodearrays[$i];//当前菜单组中的第几个
            $menu_id = $menus_array['id'];//当前递归到的当前菜单id
            //if(@$allmenus[$menu_id] == null){ //判断当前节点的id在所有菜单组中的序列里是否存在，如果存在就是顶级，否则（null）就是子级

            if(!isset($allmenus[$menu_id])){    //判断当前节点的id在所有菜单组中的序列里是否存在，如果存在就是顶级，否则（null）就是子级

                $tags = self::create_menu_tag($menus_array,$g = '',true);
            }
            else {
                $tags = self::iteration($allmenus, $menu_id, '' );//如果是顶级就把当前id当做pid递归下面的子菜单
                $tags = self::create_menu_tag($menus_array,$tags,false);
            }
            $tag = $tag . $tags;
        }*/
        foreach ($nodearrays as $key => $menu){
            $menu_id = $menu['id'];
            if(!isset($allmenus[$menu_id])){    //判断当前节点的id在所有菜单组中的序列里是否存在，如果存在就是顶级，否则（null）就是子级
                $tags = self::create_menu_tag($menu,$g = '',true);
            }
            else {
                $tags = self::iteration($allmenus, $menu_id, '' );//如果是顶级就把当前id当做pid递归下面的子菜单
                $tags = self::create_menu_tag($menu,$tags,false);
            }
            unset($menu);
            $tag = $tag . $tags;
        }
        return $tag;
    }
    /*
             protected function create_menu_tag($data,$child = '',$flag = false){

        $strs = '<li><a data-url="'.$data['url'].'" title="'.$data['title'].'">'.$data['title'].'</a></li>';
        $str = '<dl id="menu-member"><dt><i class="'.$data['class'].'">&#xe60d;</i>' .$data['name'] . '<i class="'.$data['icon'].'">&#xe6d5;</i></dt><dd>';
        if($flag){
            return $strs ;
        }else{
            return $strg = $str . '<ul>' .$child . '</ul></dd></dl>';
        }
    }
     * */
    protected function create_menu_tag($data,$child = '',$flag = false){
        $strs = '<li class=""><a data-url="'.$data['url'].'"><i class="menu-icon fa fa-caret-right"></i>'.$data['name'].'</a><b class="arrow"></b></li>';
        $str = '<li class=""><a class="dropdown-toggle"><i class="menu-icon fa fa-file-o"></i><span class="menu-text">' .$data['name'] . '</span><b class="arrow fa fa-angle-down"></b></a><b class="arrow"></b>';
        if($flag){
            return $strs ;
        }else{
            return $strg = $str . '<ul class="submenu">' .$child . '</ul></li>';
        }
    }
}
?>