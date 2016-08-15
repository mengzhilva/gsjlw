<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【幻灯片管理】功能开发(SlideshowController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月19日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月19日
// +----------------------------------------------------------------------

class SlideshowController extends CommonController {
	
	private $citylevel;

	public function __construct(){

		parent::__construct();

		$this->citylevel = $_SESSION['scadminuser']['cid'];
		$city = M('city')->where('id in ('.$this->citylevel.')')->select();
		$slds = M('slideshowclass')->select();

		$this->assign('city_list',$city);
		$this->assign('slds_list',$slds);
	}
	

	/*
	* 方法功能：幻灯片列表的展示
	* 修改时间：2014年12月19日 Jean
	*/
	function index(){
		$this->checkLevel(122);
		$map = array();
		$map['CID'] = array('in',"$this->citylevel");

		if(!empty($_POST['CID'])){
			$map['CID'] = $_POST['CID'];
		}
		if(!empty($_POST['SID'])){
			$map['SID'] = $_POST['SID'];
		}
		if($_POST['STATUS'] != ''){
			$map['STATUS'] = $_POST['STATUS'];
		}
		if(!empty($_POST['NAME'])){
			$name = $_POST['NAME'];
			$map['NAME'] = array('like',"%$name%");
		}

		//处理页码
		$p=1;
		if(!empty($_POST['pageNum'])){
			$p=$_POST['pageNum'];
		}
		$_GET['p']=$p;
		
		//处理每页条数：
		$numPerPage=18;
		if(!empty($_POST['numPerPage'])){
			$numPerPage=$_POST['numPerPage'];
		}
		
		//排序处理
		$order="id";
		$sort="desc";
		if(!empty($_POST["_order"])){
			$order=$_POST["_order"];
			$sort=$_POST["_sort"];
		}
		
		$mod = M("slideshow");
		$select = $mod->where($map)->select();
		$total = count($select);

		$page = new \Think\Page($total,$numPerPage);

		$ss_list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();

		foreach($ss_list as $key=>$val){
			$ad_lists[$key]['ID'] = $val['ID'];
			$ad_lists[$key]['NAME'] = $val['NAME'];
			$ad_lists[$key]['CID'] = $this->getCityName($val['CID']);
			$ad_lists[$key]['SID'] = $this->getSslName($val['SID']);
			$ad_lists[$key]['URL'] = $val['URL'];
			$ad_lists[$key]['STATUS'] = $val['STATUS'];
		}

		$this->assign("ss_list",$ad_lists);

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码
		
		$this->assign("package_list",$packageList);
		
		$this->assign("s_city",$this->getCityName($_POST['CID']));
		$this->assign("s_status",$this->getStatuName($_POST['STATUS']));
		$this->assign("s_name",$this->getSslName($_POST['SID']));
		$this->assign("NAME",$_POST['NAME']);
		
		$this->assign("CID",$_REQUEST['CID']);
		$this->assign("SID",$_REQUEST['SID']);
		$this->assign("STATUS",$_REQUEST['STATUS']);

		$this->display();
	}


	
	/**
	* 方法功能：判断幻灯片状态
	* 修改时间：2014年12月19日 Jean
	*/
	function getStatuName($statu){
		if($statu=="0"){
			$s = "待审核";
		}elseif($statu==1){
			$s = "审核通过";
		}elseif($statu==-1){
			$s = "审核不过";
		}
		return $s;
	}

	/**
	* 方法功能：通过ID获取城市名称
	* 修改时间：2014年12月19日 Jean
	*/
	function getCityName($id){
		$city = M("city");
		$resl = $city->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过ID获取幻灯片位置名称
	* 修改时间：2014年12月19日 Jean
	*/
	function getSslName($id){
		$adp = M("slideshowclass");
		$resl = $adp->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/*
	* 方法功能：幻灯片插入数据库
	* 修改时间：2014年12月19日 Jean
	*/
	function insert(){
		$_POST['UPDATETIME'] = date("Y-m-d H:i:s",time());
		parent::insert();
	}

	/*
	* 方法功能：幻灯片修改
	* 修改时间：2014年12月19日 Jean
	*/
	function update(){
		$_POST['UPDATETIME'] = date("Y-m-d H:i:s",time());
		parent::update();
	}

	/*
	* 方法功能：幻灯片编辑
	* 修改时间：2014年12月19日 Jean
	*/
	function edit(){
		$this->checkLevel(125);
		$id = $_GET["ID"];
		$ad = M("slideshow");
		$ad_info = $ad->where("ID=$id")->select();
		foreach($ad_info as $key=>$val){
			$ad_infos[$key]['ID'] = $val['ID'];
			$ad_infos[$key]['NAME'] = $val['NAME'];
			$ad_infos[$key]['URL'] = $val['URL'];
			$ad_infos[$key]['CID'] = $val['CID'];
			$ad_infos[$key]['CNAME'] = $this->getCityName($val['CID']);
			$ad_infos[$key]['SID'] = $val['SID'];
			$ad_infos[$key]['SNAME'] = $this->getSslName($val['SID']);
			$ad_infos[$key]['IMAGE'] = $val['IMAGE'];
			$ad_infos[$key]['endtime'] = $val['endtime'];
		}
		$this->assign("sl",$ad_infos[0]);
		$this->display();
	}

	/*
	* 方法功能：添加幻灯片动作
	* 修改时间：2014年12月19日 Jean
	*/
	function add(){
		$this->checkLevel(123);
		$this->display();
	}

	/*
	* 方法功能：审核幻灯片动作
	* 修改时间：2014年12月19日 Jean
	*/
	function check(){
		$this->checkLevel(126);
		$id = $_GET['ID'];
		$ad = M("slideshow");
		$ad_info = $ad->field("ID,NAME,STATUS,STATUSDESCRIPTION")->where("ID=$id")->select();
		$this->assign("ad",$ad_info[0]);
		$this->display();
	}

	/*
	* 方法功能：处理幻灯片审核
	* 修改时间：2014年12月19日 Jean
	*/
	function update_check(){
		$id = $_POST["ID"];
		$ad = M("slideshow");
		$data["STATUSDESCRIPTION"] = $_POST["STATUSDESCRIPTION"];
		$data["STATUS"] = $_POST["STATUS"];
		if($ad->where("ID=$id")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
	}

	
	/*
	* 方法功能：删除幻灯片动作
	* 修改时间：2014年12月19日 Jean
	*/
	function del(){
		$this->checkLevel(124);
		$id = $_REQUEST["ID"];
		if(!empty($id)){
			$ad = M("slideshow");
			if($ad->where("ID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
	}



}