<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【装修指南】文章功能开发(Tbfitmentguide.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月17日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月17日
// +----------------------------------------------------------------------

class TbfitmentguideController extends CommonController {

	private $citylevel;

	public function __construct(){

		parent::__construct();

		$this->citylevel = $_SESSION['scadminuser']['cid'];
		$city = M('city')->where('id in ('.$this->citylevel.')')->select();
		$this->assign('city_list',$city);
	}
	
	/*
	* 方法功能：封装搜索文章的条件
	* 修改时间：2014年12月25日 Jean
	*/
	public function  _filter(&$map){
		if(!empty($_POST['CityId'])){
			$map['CityId'] = $_POST['CityId']; 
		}else{
			$map['CityId'] = array('in',$this->citylevel);
		}
		if(!empty($_POST['Status'])){
			$map['Status'] = $_POST['Status']; 
		}
		if(!empty($_POST['Title'])){
			$map['Title'] = array('like',"%{$_POST['Title']}%"); 
		}
	}


	/*
	* 方法功能：文章列表的展示
	* 修改时间：2014年12月25日 Jean
	*/
	function index(){
		$this->checkLevel(99);
		parent::index();
	}


	function index_bak(){
		$this->checkLevel(99);
		$map = array();
		$map['CityId'] = array('in',"$this->citylevel");
		if(!empty($_POST['CityId'])){
			$map['CityId'] = $_POST['CityId'];
		}
		if($_POST['Status'] != ''){
			$map['Status'] = $_POST['Status'];
		}
		if(!empty($_POST['Title'])){
			$title = $_POST['Title'];
			$map['Title'] = array('like',"%$title%");
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

		$cityList = $this->getCityList();
		
		$mod = M("tbfitmentguide");
		$select = $mod->where($map)->select();
		$total = count($select);

		$page = new \Think\Page($total,$numPerPage);

		$tfg_list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();

		$this->assign("tfg_list",$tfg_list);

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码
		
		$this->assign("package_list",$packageList);
		//$this->assign("city_list",$cityList);
		
		$this->assign("s_city",$this->getCityName($_POST['CityId']));
		$this->assign("s_status",$this->getStatuName($_POST['Status']));
		$this->assign("Title",$_POST['Title']);
		
		$this->assign("CityId",$_REQUEST['CityId']);
		$this->assign("PID",$_REQUEST['PID']);
		$this->assign("Status",$_REQUEST['Status']);

		$this->display();
	}

	/**
	* 方法功能：获取上传城市列表
	* 修改时间：2014年12月9日 Jean
	*/
	function getCityList(){
		$city = M("city");
		$resl = $city->field("ID,NAME")->select();
		return $resl;
	}

	/**
	* 方法功能：获取文章类型列表
	* 修改时间：2014年12月9日 Jean
	*/
	function getWclassList(){
		$ts = M("tbfitmentguideclass");
		$resl = $ts->field("ClassID,ClassName")->select();
		return $resl;
	}

	
	/**
	* 方法功能：判断案例状态
	* 修改时间：2014年12月15日 Jean
	*/
	function getStatuName($statu){
		if($statu=="0"){
			$s = "待审核";
		}elseif($statu==1){
			$s = "审核通过";
		}elseif($statu==2){
			$s = "审核不过";
		}
		return $s;
	}

	/**
	* 方法功能：通过ID获取城市名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getCityName($id){
		$city = M("city");
		$resl = $city->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过ID获取类别名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getClassName($id){
		$class = M("tbfitmentguideclass");
		$resl = $class->field("ClassName")->where("ClassID=$id")->select();
		return $resl[0]['ClassName'];
	}

	/*
	* 方法功能：内容管理的展示
	* 修改时间：2014年12月17日 Jean
	*/
	function contentmanage(){
		$this->display();
	}

	/*
	* 方法功能：文章插入数据库
	* 修改时间：2014年12月17日 Jean
	*/
	function insert(){
		$_POST['CityName'] = $this->getCityName($_POST['CityId']);
		$_POST['ClassName'] = $this->getClassName($_POST['ClassID']);
		if(!empty($_POST['Tag'])){
			if(strpos($_POST['Tag'],'，')){
				$_POST['Tag'] = str_replace('，',',',$_POST['Tag']);
			}
		}
		parent::insert();
	}

	/*
	* 方法功能：文章修改
	* 修改时间：2014年12月17日 Jean
	*/
	function update(){
		$_POST['CityName'] = $this->getCityName($_POST['CityId']);
		$_POST['ClassName'] = $this->getClassName($_POST['ClassID']);
		if(!empty($_POST['Tag'])){
			if(strpos($_POST['Tag'],'，')){
				$_POST['Tag'] = str_replace('，',',',$_POST['Tag']);
			}
		}
		parent::update();
	}

	/*
	* 方法功能：文章编辑
	* 修改时间：2014年12月17日 Jean
	*/
	function edit(){
		$this->checkLevel(98);
		$id = $_GET["ID"];
		$dfw = M("tbfitmentguide");
		$dfw_info = $dfw->where("ID=$id")->select();
		
		$this->assign("city_list",$this->getCityList());
		$this->assign("wc_list",$this->getWclassList());
		$this->assign("dfw",$dfw_info[0]);
		$this->display();
	}

	/*
	* 方法功能：添加文章动作
	* 修改时间：2014年12月17日 Jean
	*/
	function add(){
		$this->checkLevel(96);
		$this->assign("city_list",$this->getCityList());
		$this->assign("wc_list",$this->getWclassList());
		$this->display();
	}

	/*
	* 方法功能：审核文章动作
	* 修改时间：2014年12月17日 Jean
	*/
	function check(){
		$this->checkLevel(100);
		$id = $_GET['ID'];
		$wf = M("tbfitmentguide");
		$wf_info = $wf->field("ID,Title,Statusdescription,Status")->where("ID=$id")->select();
		$this->assign("wf",$wf_info[0]);
		$this->display();
	}

	/*
	* 方法功能：处理文章审核
	* 修改时间：2014年12月18日 Jean
	*/
	function update_check(){
		$id = $_POST["ID"];
		$wf = M("tbfitmentguide");
		$data["Statusdescription"] = $_POST["Statusdescription"];
		$data["Status"] = $_POST["Status"];
		if($wf->where("ID=$id")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
	}

	
	/*
	* 方法功能：删除文章动作
	* 修改时间：2014年12月18日 Jean
	*/
	function del(){
		$this->checkLevel(97);
		$id = $_REQUEST["ID"];
		if(!empty($id)){
			$case = M("tbfitmentguide");
			if($case->where("ID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
	}


	



}