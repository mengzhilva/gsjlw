<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.2.3,葛荣
 */
class OrderController extends CommonController {

	public function index() {
		$data['TYPE']=$_REQUEST['type'];
		$data['DID']=$_REQUEST['id'];
		$this->assign('type',$_REQUEST['type']);
		$this->assign('did',$_REQUEST['id']);
		$title="预约_实创装饰";
		$this->assign('title', $title);
		$this->display('index');
	}
	public function order(){
		$UID=isset($this->user['id'])?$this->user['id']:0;
	$CID=isset($this->user['CID'])?$this->user['CID']:$this->city['ID'];
	$DID=isset($_REQUEST['DID'])?$_REQUEST['DID']:0;
	$NAME=isset($_REQUEST['NAME'])?$_REQUEST['NAME']:"";
	$PHONE=isset($_REQUEST['PHONE'])?$_REQUEST['PHONE']:"";
	$AREA=isset($_REQUEST['AREA'])? $_REQUEST['AREA']:"";
	$COMMUNITY=isset($_REQUEST['COMMUNITY'])? $_REQUEST['COMMUNITY']:"";
	$TYPE=$_REQUEST['TYPE'];

	$data['uid'] = $UID;
	$data['CID'] = $CID;
	$data['DID'] = $DID;
	$data['community'] = $COMMUNITY ;
	$data['area'] =  $AREA;
	$data['NAME'] = $NAME;
	$data['PHONE'] = $PHONE;
	$data['TYPE']=$TYPE;
	$data['TIME'] = date('Y-m-d');
    //推广员判断
    $data['PromoterId'] = isset($_REQUEST['PromoterId'])?$_REQUEST['PromoterId']:0;
	$ResultArray = M("reservation_construction");
	if($ResultArray->add($data)){
		echo "<script type='text/javascript'>alert('提交成功！');self.location=document.referrer;</script>";
	}else{
		echo "<script type='text/javascript'>alert('提交失败！');</script>";
	}

	}
	//微信公众号在线报修
	public function onlinerepair(){
		$title="在线报修_实创装饰";
		$this->assign('title', $title);
		$this->display('onlinerepair');
	}
	public function  onlineorder(){
		// print_r($_REQUEST);
		$data['NAME'] = $_REQUEST['NAME'];
		$data['PHONE'] = $_REQUEST['PHONE'];
		$data['TYPE']= 13;
		$data['TIME'] = date('Y-m-d');
	    //推广员判断
	    $data['PromoterId'] = isset($_REQUEST['PromoterId'])?$_REQUEST['PromoterId']:0;
		$ResultArray = M("reservation_construction");
		if($ResultArray->add($data)){
		echo "<script type='text/javascript'>alert('提交成功！请您保持手机通畅，售后专员会尽快与您联系！');self.location=document.referrer;</script>";
		}else{
			echo "<script type='text/javascript'>alert('提交失败！');</script>";
		}
	}


}