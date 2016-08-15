<?php
namespace Home\Controller;
use Think\Controller;
/**
*前台小区团购列表控制器(test)，继承Controller
*方法：
*开发时间：2015-01-09
*修改时间：2015-01-12
*开发人员：李彭青 
*/
class GroupController extends Controller{
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
        if(!defined('Groupbuy')){
           $model = D('Groupbuy');
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
    protected function _search($name='Groupbuy') {
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
    protected function _list($model, $map = array(), $fields = array(), $sortBy = '', $asc = false,$pnum='6') {
        
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
        $list = $model->where($map)->field($fields)->order($order.' '.$sort)->limit($p->firstRow.','.$p->listRows)->select();
                        
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
    private function _tigger_list(&$list) {

        foreach ($list as &$v) {
            $v['housetype'] = M('housetype')->where('ID='.$v['houid'])->getField('NAME');
            $v['pricelevel'] = M('pricelevel')->where('groupbuyid='.$v['id'])->order('price desc')->find();
            if($v['endtime'] > time()){
                $v['in'] = "1";
            }else{
                $v['in'] = "0";
            }
        }
     // dump($list);
     return $list;

    }

    public function _filter(&$map){
        // dump($_REQUEST);
        if(!empty($_REQUEST['keyword'])) {
            $where['name'] = array('like',"%{$_REQUEST['keyword']}%");
        }
        
        if(!empty($_REQUEST['ing'])) {
            switch($_REQUEST['ing']){
                case 1:
                    // $where['endtime'] = array('neq',null);
                    break;
                case 2:
                    $where['endtime'] = array('gt',time());
                    break;
                case 3:
                    $where['endtime'] = array('lt',time());
                    break;
            }
        }
        $where['status'] = array('eq','1');
        $where['_logic'] = 'AND';
        $map['_complex'] = $where;
    }



    /**
     *加载添加小区页面
     */
    public function add() {
        $ctname = M('city')->field('id,name')->select();
        $this->assign('ctname',$ctname);
        $this->display();
    }

    /**
     *执行添加小区页面
     */
    public function insert() {
        $model = D('Community');
        
        // 检查community表中是否已经存在此小区
        $data['NAME'] = $_POST['NAME'];
        $data['CID'] = $_POST['CID'];
        // $data['AID'] = $_POST['AID'];
        $check = M('community')->where($data)->find();
        // dump($check);
        
        // 判断小区如果存在将不再写入并提示，否则执行写入操作
        $res=array("err"=>"","msg"=>"");
        $res['state']=0;
        if(!empty($check)){
            $res['err'] = "error"; 
            $res['msg'] = "您添加的小区已经存在！";
            $this->ajaxReturn($res);
            exit;
        }else{
            $data['NAME'] = $_POST['NAME'];
            $data['CID'] = $_POST['CID'];
            $data['ADDRESS'] = $_POST['ADDRESS'];
            $data['Image'] = $_POST['Image'];

            // dump($data);
            if($model->add($data)){
                $res['state'] = 200;
                $res['msg'] = "您添加的小区已经添加成功，正在审核中！";
                $this->ajaxReturn($res);
            }
            exit;

        }
    }

    /* 添加小区中无刷新图片上传
     */
    public function upload(){
        $upload = new \Think\Upload();  //实例化上传类
        $upload->maxSize = 3145728;     //设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Public/Uploads/community/'; //设置附件上传目录 
        $upload->autoSub=false;
        // 上传文件 
        $info = $upload->upload();
        if(!$info) {
            // 上传错误提示错误信息
            echo "<script>window.parent.doShow('false');</script>"; 
        }else{
            echo "<script>window.parent.doShow('{$info['photo']['savename']}');</script>"; 
        }
        exit();
    }

    public function doUpload($path){
         $upload = new \Think\Upload();// 实例化上传类
         $upload->maxSize =3145728 ;// 设置附件上传大小 
         $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->rootPath  = './Public/'; // 设置附件上传目录
         $upload->savePath = $path; //设置附件上传目录
         $upload->autoSub = true; //开启自动生成子目录
         $upload->subName = array('date','Ymd'); //子目录
         
         // 上传文件 
         $info = $upload->upload();
         //准备响应信息
         $res=array("err"=>"","msg"=>"");
         $res['state']=0;
         if(!$info){
            // 上传错误提示错误信息
            $res['err']="上传失败：原因:".$upload->getError();
         }else{
            foreach($info as $upfile){
                // 上传成功
                $res['msg']="__PUBLIC__/".$upfile['savepath'].$upfile['savename'];

                $res['state']=200;
            }
         }
         $this->ajaxReturn($res);
    }


    public function info() {
        $id = I('get.id',0,'intval');

        M('Groupbuy')->where('id='.$id)->setInc('click');
        $list = M('Groupbuy')->find($id);

        $list['casescount'] = M('Casedecorate')->where('DID='.$list['deid'])->count();
        $list['designer'] = M('Designer')->field('phote,name')->find($list['deid']);
        $list['housetype'] = M('Housetype')->where('ID='.$list['houid'])->getField('NAME');
        $list['surplus'] = $list['number'] - (count(explode(',',$list['usersid'])));
        if($list['surplus'] < 0){
            $list['surplus'] = 0;
        }
        $list['time'] = $list['endtime'] - time();
        dump($list);
        $this->assign('vo',$list);
        $this->display('info');
    }

}
?>