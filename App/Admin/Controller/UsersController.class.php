<?php
//自定义用户信息控制器管理
namespace Admin\Controller;

class UsersController extends CommonController {
	//封装搜素条件,自动调用的方法
    public function _filter(&$map){
  
        // //查询城市
        $CID=$_REQUEST['CID'];
        $cityna = getcityname($CID);
        //查询状态
        $this->assign('MCID',$CID);
        $this->assign('cityna',$cityna);

        //搜索条件有值则做封装
        if(!empty($_REQUEST['CID'])){
          $where['CID']  = $_REQUEST['CID'];
        }
        if(!empty($_REQUEST['username'])){
          $where['username'] = array('like', "%{$_REQUEST['username']}%");
        }
        if(!empty($_REQUEST['username'])){
          $where['mobilephone'] = array('like', "%{$_REQUEST['mobilephone']}%");
        }
        $where['_logic'] = 'or';
        return $where;
    }
    public function __construct(){
    	parent::__construct();
        $this->citylevel = $_SESSION['scadminuser']['cid'];
    	//查询城市
    	$city = M("city");
    	$cityname = $city->where('id in ('.$this->citylevel.')')->select();
    	$this->assign('cityname',$cityname);
    }
     public function index() {
       //权限判断
        $this->checkLevel(39);
        //列表过滤器，生成查询Map对象
        $where = '';
        if(method_exists($this, '_filter')) {
          $where = $this->_filter();
        }
        //判断采用自定义的Model类
        $model = M('Users');
        if(!empty($model)) {
          $this->_list($model, $where);
        }
        // $this->assign('vo',$Designad);
        $this->display('index');
       
    }
 
    public function add(){
        $this->checkLevel(40);
    	
    	$this->display("add");
    }

    //用户插入数据库操作
    public function insert(){
    	header("Content-Type:text/html; charset=utf-8"); 
		 $UsersArray = D("Users");
	    if($UsersArray->create()){
	        if($lastInsId = $UsersArray->add()){
	            $this->success('新增成功,插入数据 id 为：'.$lastInsId, '/Users/index/');
	        } else {
	           $this->error('新增失败');
	        }
	    }else{
	        exit($UsersArray->getError().' [ <a href="javascript:history.back()">返 回</a> ]');
	    }
    }
    public function edit(){
        $this->checkLevel(41);
        $id=$_GET['id'];
        $UsersArray = M("Users");
        $Users=$UsersArray->where('ID='.$id)->find();
        $this->assign('vo',$Users);
        $this->display('edit');
    }
    public function update(){

        $_POST['password'] = $_POST['newpassword'];
        parent::update();
    }
    public function del() {
        $this->checkLevel(42);
        $id = $_REQUEST['ids'];print_r($$_REQUEST['ids']);
        $model = M('Users');
        if (isset($id) && is_string($id)) {
            //批量删除
            $condition = array('id' => array('in', $id));
            if (false !== $model->where($condition)->delete()) {
                $this->success(L('删除成功'));
            } else {
                $this->error(L('删除失败'));
            }
        } else if (is_array($id)){
            //批量删除
            $condition = array('id'=> array('in', explode(',', $id)));
            if (false !== $model->where($condition)->delete()) {
                $this->success(L('删除成功'));
            } else {
                $this->error(L('删除失败'));
            }
        }else {
            $this->error('非法操作');
        }
    }

}