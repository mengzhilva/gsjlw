<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【广告】相关功能开发(ActivityController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月19日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月19日
// +----------------------------------------------------------------------

class ActivityController extends CommonController {

	private $citylevel;

	public function __construct(){

		parent::__construct();

		$this->citylevel = $_SESSION['scadminuser']['cid'];

		$city = M('city')->where('id in ('.$this->citylevel.')')->select();
		$adps = M('adposition')->select();

		$this->assign('city_list',$city);
		$this->assign('adp_list',$adps);
	}

	/*
	* 方法功能：广告列表的展示
	* 修改时间：2014年12月19日 Jean
	*/
	function index(){
		$this->checkLevel(113);
		$map = array();
		$map['cid'] = array('in',"$this->citylevel");

		if(!empty($_POST['cid'])){
			$map['cid'] = $_POST['cid'];
		}
		if(!empty($_POST['pid'])){
			$map['pid'] = $_POST['pid'];
		}
		if($_POST['STATUS'] != ''){
			$map['STATUS'] = $_POST['STATUS'];
		}
		if(!empty($_POST['name'])){
			$name = $_POST['name'];
			$map['name'] = array('like',"%$name%");
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
		
		$mod = M("activity");
		$select = $mod->where($map)->select();
		$total = count($select);

		$page = new \Think\Page($total,$numPerPage);

		$ad_list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();

		foreach($ad_list as $key=>$val){
			$ad_lists[$key]['ID'] = $val['ID'];
			$ad_lists[$key]['name'] = $val['name'];
			$ad_lists[$key]['cid'] = $this->getCityName($val['cid']);
			$ad_lists[$key]['pid'] = $this->getPadName($val['pid']);
			$ad_lists[$key]['url'] = $val['url'];
			$ad_lists[$key]['startTime'] = $val['startTime'];
			$ad_lists[$key]['endtime'] = $val['endtime'];
			$ad_lists[$key]['STATUS'] = $val['STATUS'];
		}

		$this->assign("ad_list",$ad_lists);

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码
		
		$this->assign("package_list",$packageList);
		
		$this->assign("s_city",$this->getCityName($_POST['cid']));
		$this->assign("s_status",$this->getStatuName($_POST['STATUS']));
		$this->assign("p_name",$this->getPadName($_POST['pid']));
		$this->assign("name",$_POST['name']);
		
		$this->assign("cid",$_REQUEST['cid']);
		$this->assign("pid",$_REQUEST['pid']);
		$this->assign("STATUS",$_REQUEST['STATUS']);

		$this->display();
	}


	
	/**
	* 方法功能：判断案例状态
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
	* 方法功能：通过ID获取广告位名称
	* 修改时间：2014年12月19日 Jean
	*/
	function getPadName($id){
		$adp = M("adposition");
		$resl = $adp->field("name")->where("id=$id")->select();
		return $resl[0]['name'];
	}

	/*
	* 方法功能：广告插入数据库
	* 修改时间：2014年12月19日 Jean
	*/
	function insert(){
		$_POST['UPDATETIME'] = date("Y-m-d H:i:s",time());
		parent::insert();
	}

	/*
	* 方法功能：广告修改
	* 修改时间：2014年12月19日 Jean
	*/
	function update(){
		$_POST['UPDATETIME'] = date("Y-m-d H:i:s",time());
		parent::update();
	}

	/*
	* 方法功能：广告编辑
	* 修改时间：2014年12月19日 Jean
	*/
	function edit(){
		$this->checkLevel(116);
		$id = $_GET["ID"];
		$ad = M("activity");
		$ad_info = $ad->where("ID=$id")->select();
		foreach($ad_info as $key=>$val){
			$ad_infos[$key]['ID'] = $val['ID'];
			$ad_infos[$key]['name'] = $val['name'];
			$ad_infos[$key]['url'] = $val['url'];
			$ad_infos[$key]['cid'] = $val['cid'];
			$ad_infos[$key]['cname'] = $this->getCityName($val['cid']);
			$ad_infos[$key]['pid'] = $val['pid'];
			$ad_infos[$key]['pname'] = $this->getPadName($val['pid']);
			$ad_infos[$key]['startTime'] = $val['startTime'];
			$ad_infos[$key]['endtime'] = $val['endtime'];
			$ad_infos[$key]['image'] = $val['image'];
		}
		$this->assign("ad",$ad_infos[0]);
		$this->display();
	}

	/*
	* 方法功能：添加广告动作
	* 修改时间：2014年12月19日 Jean
	*/
	function add(){
		$this->checkLevel(114);
		$this->display();
	}

	/*
	* 方法功能：审核广告动作
	* 修改时间：2014年12月19日 Jean
	*/
	function check(){
		$this->checkLevel(117);
		$id = $_GET['ID'];
		$ad = M("activity");
		$ad_info = $ad->field("ID,name,STATUS,STATUSDESCRIPTION")->where("ID=$id")->select();
		$this->assign("ad",$ad_info[0]);
		$this->display();
	}

	/*
	* 方法功能：处理广告审核
	* 修改时间：2014年12月19日 Jean
	*/
	function update_check(){
		$id = $_POST["ID"];
		$ad = M("activity");
		$data["STATUSDESCRIPTION"] = $_POST["STATUSDESCRIPTION"];
		$data["STATUS"] = $_POST["STATUS"];
		if($ad->where("ID=$id")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
	}

	
	/*
	* 方法功能：删除广告动作
	* 修改时间：2014年12月19日 Jean
	*/
	function del(){
		$this->checkLevel(115);
		$id = $_REQUEST["ID"];
		if(!empty($id)){
			$ad = M("activity");
			if($ad->where("ID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
	}


	



}