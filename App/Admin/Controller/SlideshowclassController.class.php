<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【幻灯片位管理】功能开发(SlideshowclassController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月19日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月19日
// +----------------------------------------------------------------------

class SlideshowclassController extends CommonController {
	
	public function __construct(){
		parent::__construct();
	}
	
	/*
	* 方法功能：幻灯片位表页的展示
	* 修改时间：2014年12月19日 Jean
	*/
	function index(){
		$this->checkLevel(127);
		$map = array();

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
		
		$mod = M("slideshowclass");
		$select = $mod->where($map)->select();
		$total = count($select);

		$page = new \Think\Page($total,$numPerPage);

		$ssc_list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();

		$this->assign("ssc_list",$ssc_list);

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码
		
		$this->assign("s_name",$_POST['NAME']);

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
	* 方法功能：幻灯片位添加
	* 修改时间：2014年12月19日 Jean
	*/
	function add(){
		$this->checkLevel(128);
		$this->display();
	}


	/**
	* 方法功能：幻灯片位删除
	* 修改时间：2014年12月19日 Jean
	*/
	function del(){
		$this->checkLevel(129);
		$id = $_REQUEST["ID"];
		if(!empty($id)){
			$case = M("slideshowclass");
			if($case->where("ID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
		
	}


	/**
	* 方法功能：幻灯片位编辑
	* 修改时间：2014年12月19日 Jean
	*/
	function edit(){
		$this->checkLevel(130);
		$id = $_REQUEST['ID'];
		if(!empty($id)){
			$case = M("slideshowclass");
			$res = $case->where("ID=$id")->select();
			$this->assign("s",$res[0]);
			$this->display();
		}
	}


}