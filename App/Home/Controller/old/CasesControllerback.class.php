<?php
namespace Home\Controller;
use Think\Controller;
/**
*前台小区案例列表控制器(test)，继承Controller
*方法：
*开发时间：2015-01-09
*修改时间：2015-01-12
*开发人员：李彭青 
*!!!!!
*/
class CasesController extends Controller{
    protected $city;
    //在Controller类中构造方法执行后则会自动调用的方法。
    public function _initialize(){

        $cityname = I('get.city',0);
        if(!empty($cityname)){
            $city = M('city')->where("DOMAIN='".$cityname."'")->select();
             
        }else{
            $city = M('city')->where("DOMAIN='beijing2'")->select();
        }
        $this->city = $city[0];
    }


    
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        //判断采用自定义的Model类
        if(!defined('Casedecorate')){
           $model = D('Casedecorate');
        }
        
        if(!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }

        /**
     * 根据表单生成查询条件
     * 进行列表过滤
     * @param string $name 数据对象名称
     * @return HashMap
     */
    protected function _search($name='Casedecorate') {
        //生成查询条件
        if (empty($name)) {
            $name = CONTROLLER_NAME;
        }
        $model = M($name);
        $map = array();
        foreach ($model->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_')
                continue;
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                $map[$val] = $_REQUEST[$val];
            }
        }

        return $map;
    }
    
    /**
     * 根据表单生成查询条件
     * 进行列表过滤
     * @param Model $model 数据对象
     * @param HashMap $map 过滤条件
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
     */
    protected function _list($model, $map = array(), $fields = array(), $sortBy = '', $asc = false,$pnum='12') {
        
        //排序字段 默认为主键名
        if (!empty($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        } else {
            $order = !empty($sortBy)?$sortBy:$model->getPk();
        }
        
        //排序方式默认按照倒序排列
        //接受 sort参数 0 表示倒序 非0都 表示正序
        if (!empty($_REQUEST['_sort'])) {
            $sort = $_REQUEST['_sort'] == 'asc'?'asc':'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        
        //取得满足条件的记录数
        $count = $model->where($map)->count();
        
        //每页显示的记录数
        if (!empty($_REQUEST['numPerPage'])) {
            $listRows = $_REQUEST['numPerPage'];
        } else if(!empty($pnum)){
            $listRows = $pnum;
        }else{
            $listRows = '20';
        }
        
        
        //设置当前页码
        if(!empty($_REQUEST['p'])) {
            $nowPage=$_REQUEST['p'];
        }else{
            $nowPage=1;
        }
        $_GET['p']=$nowPage;
        
        //创建分页对象
        $p = new \Think\Page($count, $listRows);
        
        
        //分页查询数据
        $list = $model->where($map)->field($fields)->order($order.' '.$sort)
                        ->limit($p->firstRow.','.$p->listRows)
                        ->select();
                        
        //回调函数，用于数据加工，如将用户id，替换成用户名称
        if (method_exists($this, '_tigger_list')) {
            $this->_tigger_list($list);
        }
        
        
        //分页跳转的时候保证查询条件
        foreach ($map as $key => $val) {
            if (!is_array($val)) {
                $p->parameter .= "$key=" . urlencode($val) . "&";
            }
        }
    
        //分页显示
        $page = $p->show();
        
        //列表排序显示
        $sortImg = $sort;                                 //排序图标
        $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列';   //排序提示
        $sort = $sort == 'desc' ? 1 : 0;                  //排序方式
        
        
        //模板赋值显示
        $this->assign('list', $list);
        // dump($list);
        $this->assign('sort', $sort);
        $this->assign('order', $order);
        $this->assign('sortImg', $sortImg);
        $this->assign('sortType', $sortAlt);
        $this->assign("page", $page);
        
        $this->assign("search",         $search);           //搜索类型
        $this->assign("values",         $_POST['values']);  //搜索输入框内容
        $this->assign("totalCount",     $count);            //总条数
        $this->assign("numPerPage",     $p->listRows);      //每页显多少条
        $this->assign("currentPage",    $nowPage);          //当前页码

    }

    /************************************************************/

    /**
     * 对数据进行加工
     */
    public function _tigger_list(&$list) {

        foreach ($list as &$v) {
            $v['groupbuylist'] = M('Groupbuy')->field('id,name,picurl')->where('status=1 AND comid='.$v['CID'])->order('id desc')->find();

            $where['status'] = array('eq','1');
            $where['id'] = array('eq',$v['CID']);
            $where['_logic'] = 'AND';
            $map['_complex'] = $where;

            $v['community'] = M('Community')->field('id,aid,name,address,image')->where($map)->find();

        }

        $cityarea = M('cityarea')->where('cid='.$this->city['ID'])->field('id,name')->select();
        $housestyle = M('housestyle')->field('id,fontname,backname')->select();
        $housetype = M('housetype')->field('id,name')->select();
        // dump($cityarea);
        // dump($housestyle);
        // dump($housetype);
        $this->assign('cityarea',$cityarea);
        $this->assign('housestyle',$housestyle);
        $this->assign('housetype',$housetype);
        dump($list);
        return $list;

    }

    public function _filter(&$map){
        dump($_REQUEST);
        if(!empty($_REQUEST['aid'])) {
            $aa = M('Community')->where('AID='.$_REQUEST['aid'])->field('id')->select();
            foreach ( $aa as $v){
                $cids[] = $v['id'];
            }

            $cid = implode(",", $cids);
            
            $where['CID'] = array('in',$cid);
        }

        if(!empty($_REQUEST['style'])) {
            $where['sid'] = array('eq',$_REQUEST['style']);
        }

        if(!empty($_REQUEST['type'])) {
            $where['typeid'] = array('eq',$_REQUEST['type']);
        }

        if(!empty($_REQUEST['area'])) {
            switch($_REQUEST['area']) {
                case 1:
                    $where['AREA'] = array('lt',60);
                    break;
                case 2:
                    $where['AREA'] = array('between','60,90');
                    break;
                case 3:
                    $where['AREA'] = array('between','90,120');
                    break;
                case 4:
                    $where['AREA'] = array('between','120,160');
                    break;
                case 5:
                    $where['AREA'] = array('between','160,200');
                    break;
                case 6:
                    $where['AREA'] = array('gt','200');
                    break;
            }
        }

        $where['status'] = array('eq','1');
        $where['_logic'] = 'AND';
        $map['_complex'] = $where;
        
    }

}
?>