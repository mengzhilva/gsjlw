<?php
//自定义用户信息控制器管理
namespace Admin\Controller;

class DesignerController extends CommonController {

    public function __construct(){
    	parent::__construct();
    	//查询城市
    	$city = M("city");
    	$cityname = $city->select();
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

    //用户插入数据库操作
    public function insert(){
    	header("Content-Type:text/html; charset=utf-8");
    	 $UsersArray = M("Users");//实例化
		//此操作时用户表写入的数据
		$userdata['CID'] = $_POST["CID"];
		$userdata['truename'] = $_POST["NAME"];//真实姓名
		$userdata['usertype'] = '1';//用户类型默认设计师
		$userdata['PHOTO'] = $_POST["background"];
		$userdata['username'] = trim($_POST['username']);
		$userdata['password'] = trim(md5($_POST['password']));
		$userdata['registerTime'] = date('Y-m-d H:i:s',time());
	    $userdata['mobilephone'] = trim($_POST['mobilephone']);
	    //判断用户名字唯一，防止重复数据录入
	    $UsersSel = $UsersArray->where("username='{$_POST['username']}'")->find();
	     if($UsersSel){
	     	$this->error('用户名已存在！');
	     }else{
	     	$UsersArray->add($userdata);
	     }
	    
    	$RESUME = formathtml($_POST["RESUME"]);
    	$_POST["RESUME"] = $RESUME;
    	$_POST['HID'] = reqs("HID");
    	$_POST['TAG'] = reqs("TAG");
    	$_POST['STATUS'] = '0';
    	$_POST['UPDATETIME'] = date("Y-m-d H:i:s");
    	$DesignerArray = M("Designer");
    	$DesignerSel = $DesignerArray->where("NAME='{$_POST['NAME']}'")->find();
    	if($DesignerSel){
	     	$this->error('设计师已存在!');
	     }else{
	     	parent::insert();
	     }


		//  $DesignerArray = M("Designer");

		//  //此操作表是设计师表构建写入的数据数组
		// $data['CID'] = $_POST["CID"];
		// $data['LID'] = $_POST["LID"];
		// $data['SID'] = $_POST['SID'];
		// $data['NAME'] = $_POST["NAME"];
		// $data['ENTRYTIME'] = $_POST["ENTRYTIME"];
		// $data['VISITS'] = $_POST["VISITS"];
		// $data['ESERVATIONS'] = $_POST["RESERVATIONS"];
		// $data['GOOD'] = $_POST["GOOD"];
		// $data['ISVIP'] = $_POST["ISVIP"];
		// $data['STAR'] = $_POST["STAR"];
		// $data['IDEA'] = trim($_POST["IDEA"]);
		// $data['HID'] = reqs("HID");
		// $data['RANK'] = $_POST["RANK"];
		// $data['RESUME'] = formathtml($_POST["RESUME"]);
		// $data['PHOTE'] = $_POST["background"];
		// $data['UPDATETIME'] = date('Y-m-d H:i:s',time());
		// $data['TAGS'] = reqs("TAG");//设计师标签
		// $data['WORKINGLIFE'] = trim($_POST['WORKINGLIFE']);//从业年限
		// $data['STATUS'] = '0';

		// $UsersArray = M("Users");//实例化
		// //此操作时用户表写入的数据
		// $userdata['CID'] = $_POST["CID"];
		// $userdata['truename'] = $_POST["NAME"];//真实姓名
		// $userdata['usertype'] = '1';//用户类型默认设计师
		// $userdata['PHOTO'] = $_POST["background"];
		// $userdata['username'] = trim($_POST['username']);
		// $userdata['password'] = trim(md5($_POST['password']));
		// $userdata['registerTime'] = date('Y-m-d H:i:s',time());
	 //    $userdata['mobilephone'] = trim($_POST['mobilephone']);
	 //    //判断设计师姓名唯一，防止重复数据录入
	 //     $DesignerSel = $DesignerArray->where("NAME='{$_POST['NAME']}'")->find();

	 //    //判断用户名字唯一，防止重复数据录入
	 //     $UsersSel = $UsersArray->where("username='{$_POST['username']}'")->find();
	 //     if($UsersSel){
	 //     	echo wscript("alert('用户名已经被占用，请选择用其它的');history.back();");
		// 	exit();
	 //     }
	    
	 //     if($DesignerSel){
	 //     	echo wscript("alert('设计师名字已经被占用，请选择用其它的');history.back();");
		// 	exit();
	 //     }
  //    	// 写入设计师数据
	 //    if($DesignerArray->add($data) && $UsersArray->add($userdata)){
	 //         $this->success('新增成功!');
	 //    } else {
	 //        exit($this->error('新增失败！').' [ <a href="javascript:history.back()">返 回</a> ]');
	 //    }
     
    }

    //编辑
    public function edit(){

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
    	$_POST['TAG'] = reqs("TAG");
    	$_POST['UPDATETIME'] = date("Y-m-d H:i:s");

		parent::update();

// $DesignerArray-> where('ID='.$id)->save($Data);
	// $id = $_POST['id'];
	// $CID = $_POST["CID"];
	// $LID = $_POST["LID"];
	// $SID = $_POST['SID'];
	// $NAME = $_POST["NAME"];
	// $ENTRYTIME = $_POST["ENTRYTIME"];
	// $VISITS = $_POST["VISITS"];
	// $RESERVATIONS = $_POST["RESERVATIONS"];
	// $GOOD = $_POST["GOOD"];
	// $ISVIP = $_POST["ISVIP"];
	// $STAR = $_POST["STAR"];
	// $IDEA = formathtml($_POST["IDEA"]);
	// $HID = reqs("HID");
	// $RESUME = formathtml($_POST["RESUME"]);
	// $PHOTE = $_POST["background"];
	// $RANK = $_POST["RANK"];
	// $TAGS = reqs("TAG");//设计师标签
	// $UPDATETIME = date('Y-m-d H:i:s',time());
 //  	$WORKINGLIFE=trim($_POST['WORKINGLIFE']);//从业年限
 //  	$STATUS = '0';

	// $updatesql = "update designer set ";
	// $updatesql = $updatesql . "CID = $CID,";
	// $updatesql = $updatesql . "LID = $LID,";
	// $updatesql = $updatesql . "SID = $SID,";
	// $updatesql = $updatesql . "NAME = '$NAME',";
	// $updatesql = $updatesql . "ENTRYTIME = '$ENTRYTIME',";
	// $updatesql = $updatesql . "VISITS = '$VISITS',";
	// $updatesql = $updatesql . "RESERVATIONS = '$RESERVATIONS',";
	// $updatesql = $updatesql . "GOOD = '$GOOD',";
	// $updatesql = $updatesql . "ISVIP = '$ISVIP',";
	// $updatesql = $updatesql . "STAR = '$STAR',";
	// $updatesql = $updatesql . "RANK = '$RANK',";
	// $updatesql = $updatesql . "IDEA = '$IDEA',";
	// $updatesql = $updatesql . "HID = '$HID',";
	// $updatesql = $updatesql . "RESUME = '$RESUME',";
	// $updatesql = $updatesql . "PHOTE = '$PHOTE',";
 //  	$updatesql = $updatesql . "TAGS = '$TAGS',";
 //  	$updatesql = $updatesql . "WORKINGLIFE = '$WORKINGLIFE',";
	// $updatesql = $updatesql . "UPDATETIME = '$UPDATETIME',STATUS=0";
	// $updatesql = $updatesql . " Where ID=$id";
	// $model = M( "Designer" );
	// $model->query($updatesql);
	
    }

}