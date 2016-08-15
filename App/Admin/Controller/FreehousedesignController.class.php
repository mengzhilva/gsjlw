<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【免费户型设计】功能开发(FreehousedesignController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月24日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月24日
// +----------------------------------------------------------------------

class FreehousedesignController extends CommonController {

	private $citylevel;

	public function __construct(){

		parent::__construct();

		$this->citylevel = $_SESSION['scadminuser']['cid'];

		$city = M('city')->where('id in ('.$this->citylevel.')')->select();
		$desi = M('designer')->select();

		$this->assign('city_list',$city);
		$this->assign('desi_list',$desi);
	}

	/**
	* 方法功能：封装搜索条件
	* 修改时间：2014年12月24日 Jean
	*/
	public function _filter(&$map){
		if(!empty($_POST['NAME'])){
			$map['NAME'] = array('like',"%{$_POST['NAME']}%");
		}
		if(!empty($_POST['CID'])){
			$map['CID'] = $_POST['CID'];
		}else{
			$map['CID'] = array('in',$this->citylevel);
		}
		if(!empty($_POST['STATUS'])){
			$map['STATUS'] = $_POST['STATUS'];
		}
	}


	/*
	* 方法功能：免费户型申请列表的展示
	* 修改时间：2014年12月24日 Jean
	*/
    public function index() {
		$this->checkLevel(138);
		parent::index();
    }

	
	/*
	* 方法功能：审核预约动作
	* 修改时间：2014年12月24日 Jean
	*/
	function check(){
		$this->checkLevel(140);
		$id = $_GET['ID'];
		$ad = M("freehousedesign");
		$ad_info = $ad->field("ID,NAME,STATUS")->where("ID=$id")->select();
		$this->assign("ad",$ad_info[0]);
		$this->display();
	}

	/*
	* 方法功能：免费户型申请列表的展示
	* 修改时间：2014年12月24日 Jean
	*/
    public function del() {
		$this->checkLevel(139);
		parent::del();
    }



}