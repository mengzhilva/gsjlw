<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【广告位】功能开发(AdpositionController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月19日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月19日
// +----------------------------------------------------------------------

class AdpositionController extends CommonController {

	public function __construct(){
		parent::__construct();
	}
	
	/*
	* 方法功能：广告位列表页的展示
	* 修改时间：2014年12月19日 Jean
	*/
	function index(){
		$this->checkLevel(118);
		$map = array();

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
		
		$mod = M("adposition");
		$select = $mod->where($map)->select();
		$total = count($select);

		$page = new \Think\Page($total,$numPerPage);

		$adp_list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();

		$this->assign("adp_list",$adp_list);

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码
		
		$this->assign("s_name",$_POST['name']);

		$this->display();
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
	* 方法功能：广告位添加
	* 修改时间：2014年12月19日 Jean
	*/
	function add(){
		$this->checkLevel(119);
		$this->display();
	}


	/**
	* 方法功能：广告位删除
	* 修改时间：2014年12月19日 Jean
	*/
	function del(){
		$this->checkLevel(120);
		$id = $_REQUEST["id"];
		if(!empty($id)){
			$case = M("adposition");
			if($case->where("id=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
		
	}


	/**
	* 方法功能：广告位编辑
	* 修改时间：2014年12月19日 Jean
	*/
	function edit(){
		$this->checkLevel(121);
		$id = $_REQUEST['id'];
		if(!empty($id)){
			$case = M("adposition");
			$res = $case->where("id=$id")->select();
			$this->assign("p",$res[0]);
			$this->display();
		}
	}

}