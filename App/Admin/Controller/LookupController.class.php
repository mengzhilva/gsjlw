<?php
namespace Admin\Controller;
/*
*搜索小区控制器，继承CommonController
*方法：
*       
*作者：李彭青 
*/
class LookupController extends CommonController {

    private $citylevel;
    public function __construct(){
        parent::__construct();

        // $this->cityarea = M('Cityarea');
        // 获取当前用户所拥有权限的城市ID
        $this->citylevel = $_SESSION['scadminuser']['cid'];

        // 获取拥有权限城市的数据
        $city = M('city')->where('id in ('.$this->citylevel.')')->select();
        $this->assign('city',$city);
    }

    /* 
    *封装搜素条件,自动调用的方法
     */
    public function _filter(&$map){
        
        //搜索条件有值则做封装
        if( !empty($_REQUEST['comname']) ){
            $where['NAME']  = array('like', "%{$_REQUEST['comname']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            
        }

        // 只显示拥有城市权限的小区
        $map['CID'] = array('in',$this->citylevel);

        // 如果城市名称(CID)有值则进行封装搜索
        if(!empty($_REQUEST['CID'])){
           $map['CID'] = array('in',$_REQUEST['CID']);
        }
    }

    /*
     *加载小区查找带回页面
     */
    public function index() {
        
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $model = M('community');
        if(!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }

    /*
     *使用 CommonController回调函数_tigger_list，用于数据加工
     */
    public function _tigger_list(&$list){

        // 将list数据遍历后作相应处理
        foreach($list as &$v){

            // 处理输出审核状态
            switch($v['STATUS']){
                case 0:
                    $v['status'] = '审核';
                    break;
                case 1:
                    $v['status'] = '通过';
                    break;
                case -1:
                    $v['status'] = '不通过';
                    break;
            }

            // 处理输出是否重点小区
            switch($v['IsImportent']){
                case 0:
                    $v['isimportent'] = '否';
                    break;
                case 1:
                    $v['isimportent'] = '是';
                    break;
            }
        }
        // 将处理后的数据返回
        return $list;
    }
    


}