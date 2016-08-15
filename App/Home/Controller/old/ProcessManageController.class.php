<?php
namespace Home\Controller;
/**
 * 会员中心控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class ProcessManageController extends UserCenterController {
	protected  $uid;
	public function _initialize(){
		parent::_initialize();
	
		// if(empty($_SESSION[C('SESSION_USER_KEY')])){
		// 	$url    =   U('Home/User/login');
		// 	$str    = "<script>var url = '{$url}';window.location.href = url;</script>";
		// 	exit($str);
		// }
		//$uid = $this->user['id'];
		//$uid = 46;
		$_SESSION["uid"] = 46;
		//$this->assign('uid', $uid);
		//$this->user = $_SESSION[C('SESSION_USER_KEY')];
	
	}

	//装修验收
	public function zxys(){
		$this->display('zxys');
	}
	//材料中心
	public function clzx(){
		$this->display('clzx');
	}	
	//材料中心-材料变更单
	public function clzx_clbgd(){
		$this->display('clzx_clbgd');
	}
	//材料中心-工艺变更单
	public function clzx_gybgd(){
		$this->display('clzx_gybgd');
	}
	//材料中心-拆项内容
	public function clzx_cxnr(){
		$this->display('clzx_cxnr');
	}
	//财务中心
	public function cwzx(){
		$this->display('cwzx');
	}	
	//装修进度
	public function zxjd(){
		$this->display('zxjd');
	}	
	//绑定合同
	public function bdht(){
		$this->display('bdht');
	}	
}