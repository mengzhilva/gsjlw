<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【装修指南】分类功能开发(Tbfitmentguide.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月17日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月17日
// +----------------------------------------------------------------------

class TbfitmentguideclassController extends CommonController {

	public function __construct(){
		parent::__construct();
	}

	/*
	* 方法功能：二级分类的展示
	* 修改时间：2014年12月17日 Jean
	*/
	function index(){
		$this->checkLevel(94);
		$map = array();
		if(!empty($_POST['ClassName'])){
			$name=$_POST['ClassName'];
			$map['ClassName'] = array('like',"%$name%");
		}
		$tfg = M("tbfitmentguideclass");
		$tfg_list = $tfg->where($map)->order("OrderID asc")->select();

		$this->assign("s_name",$_POST['ClassName']);
		$this->assign("tfg_list",$tfg_list);

		$this->display();
	}


	/*
	* 方法功能：分类管理的编辑
	* 修改时间：2014年12月17日 Jean
	*/
	function edit(){
		$this->checkLevel(92);
		$id = $_REQUEST['ClassID'];
		$tfg = M("tbfitmentguideclass");
		$tfg_info = $tfg->where("ClassID=$id")->select();
		$this->assign("tfg_info",$tfg_info[0]);
		$this->display();
	}

	/*
	* 方法功能：分类添加
	* 修改时间：2014年12月17日 Jean
	*/
	function insert(){
		parent::insert();
	}

	/*
	* 方法功能：提交分类修改信息
	* 修改时间：2014年12月17日 Jean
	*/
	function update(){
		parent::update();
	}

	/*
	* 方法功能：分类删除
	* 修改时间：2014年12月18日 Jean
	*/
	function del(){
		$this->checkLevel(93);
		$id = $_REQUEST["ClassID"];
		if(!empty($id)){
			$case = M("tbfitmentguideclass");
			if($case->where("ClassID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
	}

	/*
	* 方法功能：分类添加
	* 修改时间：2014年12月17日 Jean
	*/
	function add(){
		$this->checkLevel(91);
		$this->display();
	}

	



}