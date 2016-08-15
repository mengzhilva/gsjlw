<?php
namespace Admin\Controller;
/*
*搜索设计师控制器，继承CommonController
*方法：
*       
*作者：李彭青 
*/
class LookdesignerController extends CommonController {

    /*封装搜素条件,自动调用的方法
     */
    public function _filter(&$map){

        //如果搜索条件dname(设计师名称)有值则做封装
        if( !empty($_REQUEST['dname']) ){
            $where['NAME']  = array('like', "%{$_REQUEST['dname']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where; 
        }
    }


    /*加载查找设计师页面
    */
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $model = M('designer');
        if(!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }
}