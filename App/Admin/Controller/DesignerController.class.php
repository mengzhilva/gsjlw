<?php
//自定义用户信息控制器管理
namespace Admin\Controller;

class DesignerController extends CommonController {
    //封装搜素条件,自动调用的方法
    public function _filter(&$map){
        //搜索条件有值则做封装
        if(!empty($_REQUEST['SNAME'])){
            $where['NAME']  = array('like',"%{$_REQUEST['SNAME']}%");
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        if(!empty($_REQUEST['CID'])){
          $where['CID']  = $_REQUEST['CID'];
        }
        if(!empty($_REQUEST['STATUS'])){
            $where['STATUS']  = array('like',"%{$_REQUEST['STATUS']}%");
        }
        //查询城市
        $CID=$_REQUEST['CID'];
        $cityna = getcityname($CID);
        //查询状态
        $STATUSID = $_REQUEST['STATUS'];
        switch ($STATUSID) {
            case '0':
                $STATUS="待审核";
                break;
            case '1':
                 $STATUS="审核通过";
                break;
            case '-1':
                 $STATUS="审核不过";
                break;
        }

        $this->assign('MCID',$CID);
        $this->assign('cityna',$cityna);
        $this->assign('STATUSID',$STATUSID);
        $this->assign('STATUS',$STATUS);

    }

    public function __construct(){
    	parent::__construct();
        //权限管理
        $this->checkLevel(6);
    	//查询城市
        $this->citylevel = $_SESSION['scadminuser']['cid']; //
    	$city = M("city");
    	$cityname = $city->where('id in ('.$this->citylevel.')')->select();
    	$this->assign('cityname',$cityname);

    	//查询设计师级别
    	$level = M("designerlevel");
    	$levelname = $level->select();
    	$this->assign('levelname',$levelname);

    	//获取设计师擅长风格
    	$style = M("housestyle");
    	$housestyle = $style->select();
    	$this->assign('housestyle',$housestyle);

    	//获取设计师标签
    	$tag = M("designertag");
    	$tagname = $tag->where('STATUS=1')->select();
    	$this->assign('tagname',$tagname);

    	//获取店铺
    	$storefront = M("storefront");
    	$store = $storefront->select();
    	$this->assign('store',$store);

    	// $this->display("add");
    }
    public function add(){
        $this->checkLevel(7);
        $this->display("add");
    }

    //用户插入数据库操作
    public function insertadd(){
    	
		//此操作时用户表写入的数据
		$data['CID'] = $_POST["CID"];
		$data['truename'] = $_POST["NAME"];//真实姓名
		$data['usertype'] = '1';//用户类型默认设计师
		$data['PHOTO'] = $_POST["PHOTE"];
		$data['username'] = trim($_POST['username']);
		$data['password'] = trim(md5($_POST['password']));
		$data['registerTime'] = date('Y-m-d H:i:s',time());
	    $data['mobilephone'] = trim($_POST['mobilephone']);
        $data['registstatus'] = 0;
        $data['coupon'] = 0;
	    //判断用户名字唯一，防止重复数据录入
       $user = M("users")->where("username='{$_POST['username']}'")->find(); //
	     if($user){
	     	$this->error('用户名已存在！');exit;
	     }else{
             M("Users")->add($data);
         }

    	$RESUME = formathtml($_POST["RESUME"]);
    	$_POST["RESUME"] = $RESUME;
    	$_POST['HID'] = reqs("HID");
    	$_POST['TAGS'] = reqs("TAGS");
    	$_POST['STATUS'] = '0';
    	$_POST['UPDATETIME'] = date("Y-m-d H:i:s");
    	$DesignerArray = M("Designer");
    	$DesignerSel = $DesignerArray->where("NAME='{$_POST['NAME']}'")->find();
    	if($DesignerSel){
	     	$this->error('设计师已存在!');exit;
	     }else{
            parent::insert();
         }

    }

    //编辑
    public function edit(){
        $this->checkLevel(8);
    	$DesignerArray = M("Designer");
    	$DesignerSel = $DesignerArray->where("ID='{$_GET['id']}'")->find();
    	$this->assign('vo',$DesignerSel);
    	$this->display("edit");
    }
    //编辑数据库操作
    public function update(){
    	$RESUME = formathtml($_POST["RESUME"]);
    	$_POST["RESUME"] = $RESUME;
    	$_POST['HID'] = reqs("HID");
    	$_POST['TAGS'] = reqs("TAGS");
    	$_POST['UPDATETIME'] = date("Y-m-d H:i:s");

		parent::update();
	
    }
    //删除数据
    public function del(){
        $this->checkLevel(9);
        $id = $_REQUEST['ids'];
        $model = M('Designer');
        if (isset($id) && is_string($id)) {
            //批量删除
            $condition = array('ID' => array('in', $id));
            if (false !== $model->where($condition)->delete()) {
                $this->success(L('删除成功'));
            } else {
                $this->error(L('删除失败'));
            }
        } else if (is_array($id)){
            //批量删除
            $condition = array('ID'=> array('in', explode(',', $id)));
            if (false !== $model->where($condition)->delete()) {
                $this->success(L('删除成功'));
            } else {
                $this->error(L('删除失败'));
            }
        }else {
            $this->error('非法操作');
        }
    }
    //设计师审核
    function check(){
        $this->checkLevel(10);
        $id = $_REQUEST['id'];
        $DesignerArray = M("Designer");
        $DesignerSel = $DesignerArray->where("ID='{$id}'")->find();
        $this->assign("id",$id);
        $this->assign("vo",$DesignerSel);
        $this->display();
    }
 

}