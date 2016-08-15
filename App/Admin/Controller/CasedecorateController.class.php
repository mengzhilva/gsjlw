<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-案例部分功能开发(CaseController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月9日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月9日
// +----------------------------------------------------------------------

class CasedecorateController extends CommonController {
	
	private $citylevel;

	public function __construct(){

		parent::__construct();

		$this->citylevel = $_SESSION['scadminuser']['cid'];
		$city = M('city')->where('id in ('.$this->citylevel.')')->select();
		$this->assign('city_list',$city);
	}
	
	/*
	* 方法功能：案例列表页的展示
	* 修改时间：2014年12月9日 Jean
	*/
	function index_bak(){
		$this->checkLevel(62);
		$case = D("casedecorate");
		$list = $case->relation(true)->limit(1)->select();
		var_dump($list);
		
	}
	function index(){
		$this->checkLevel(62);
		$map = array();
		$map['cityID'] = array('in',"$this->citylevel");
		if(!empty($_POST['cityID'])){
			$map['cityID'] = $_POST['cityID'];
		}
		if(!empty($_POST['PID'])){
			$map['PID'] = $_POST['PID'];
		}
		if($_POST['status'] != ''){
			$map['status'] = $_POST['status'];
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

		$cityList = $this->getCityList();
		$packageList = $this->getPackageList();
		
		$mod = M("casedecorate");
		$select = $mod->where($map)->select();
		$total = count($select);

		$page = new \Think\Page($total,$numPerPage);

		$case_list = $mod->where($map)->order($order." ".$sort)->limit($page->firstRow.','.$page->listRows)->select();

		foreach($case_list as $key=>$val){
			$result[$key]["ID"]         = $val["ID"];
			$result[$key]["NAME"]       = $val["NAME"];
			$result[$key]["cityID"]     = $this->getCityName($val["cityID"]);
			$result[$key]["PID"]        = $this->getPackageName($val["PID"]);
			$result[$key]["AREA"]       = $val["AREA"];
			$result[$key]["IS3D"]       = $val["IS3D"];
			$result[$key]["TYPEID"]     = $this->getHuName($val["TYPEID"]);
			$result[$key]["CID"]        = $this->getQuName($val["CID"]);
			$result[$key]["DID"]        = $this->getDesignerName($val["DID"]);
			$result[$key]["UPDATETIME"] = $val["UPDATETIME"];
			$result[$key]["status"]     = $val["status"];
		}


		$this->assign("case_list",$result);

		//页码信息的设置
		$this->assign("totalCount",$total); //总数据条数
		$this->assign("numPerPage",$numPerPage); //每页数据条数
		$this->assign("currentPage",$p); //当前页码
		
		$this->assign("package_list",$packageList);
		//$this->assign("city_list",$cityList);
		
		$this->assign("s_city",$this->getCityName($_POST['cityID']));
		$this->assign("s_package",$this->getPackageName($_POST['PID']));
		$this->assign("s_status",$this->getStatuName($_POST['status']));
		$this->assign("s_name",$_POST['NAME']);
		
		$this->assign("cityID",$_REQUEST['cityID']);
		$this->assign("PID",$_REQUEST['PID']);
		$this->assign("status",$_REQUEST['status']);

		$this->display();
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
	* 方法功能：通过ID获取案例名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getCaseName($id){
		$casedecorate = M("casedecorate");
		$resl = $casedecorate->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	function getCaseDID($id){
		$casedecorate = M("casedecorate");
		$resl = $casedecorate->field("DID")->where("ID=$id")->select();
		return $resl[0]['DID'];
	}
	
	/**
	* 方法功能：通过ID获取户型名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getHuName($id){
		$hu = M("housetype");
		$resl = $hu->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过ID获取套餐名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getPackageName($id){
		$package = M("package");
		$resl = $package->field("NAME")->where("HTYPE=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过ID获取小区名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getQuName($id){
		$community = M("community");
		$resl = $community->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过ID获取小区名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getDesignerName($id){
		$designer = M("designer");
		$resl = $designer->field("NAME")->where("ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过色系名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getColorName($id){
		$case_color = M("case_color");
		$resl = $case_color->field("NAME")->where("COLO_ID=$id")->select();
		return $resl[0]['NAME'];
	}

	/**
	* 方法功能：通过风格名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getStyleName($id){
		$housestyle = M("housestyle");
		$resl = $housestyle->field("BACKNAME")->where("ID=$id")->select();
		return $resl[0]['BACKNAME'];
	}

	/**
	* 方法功能：通过空间名称
	* 修改时间：2014年12月9日 Jean
	*/
	function getKongName($id){
		$housefunction = M("housefunction");
		$resl = $housefunction->field("BACKNAME")->where("ID=$id")->select();
		return $resl[0]['BACKNAME'];
	}



	/**
	* 方法功能：获取案例列表信息
	* 修改时间：2014年12月9日 Jean
	*/
	function getCaseListInfo($info){
		$case = M("casedecorate");
		$map = array();
		if(!empty($info['cityID']) || !empty($info['PID']) || !empty($info['status']) || !empty($info['NAME'])){
			$map['cityID'] = $info["cityID"];
			$map['PID']    = $info["PID"];
			$map['status'] = $info["status"];
			$map['NAME']   = $info["NAME"];
			$case_list = $case->where($map)->order("ID desc")->limit(20)->select();
		}else{
			$case_list = $case->order("ID desc")->limit(20)->select();
		}

		foreach($case_list as $key=>$val){
			$result[$key]["ID"]         = $val["ID"];
			$result[$key]["NAME"]       = $val["NAME"];
			$result[$key]["cityID"]     = $this->getCityName($val["cityID"]);
			$result[$key]["PID"]        = $this->getPackageName($val["PID"]);
			$result[$key]["AREA"]       = $val["AREA"];
			$result[$key]["IS3D"]       = $val["IS3D"];
			$result[$key]["TYPEID"]     = $this->getHuName($val["TYPEID"]);
			$result[$key]["CID"]        = $this->getQuName($val["CID"]);
			$result[$key]["DID"]        = $this->getDesignerName($val["DID"]);
			$result[$key]["UPDATETIME"] = $val["UPDATETIME"];
			$result[$key]["status"]     = $val["status"];
		}

		return $result;
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
	* 方法功能：获取上传套餐列表
	* 修改时间：2014年12月9日 Jean
	*/
	function getPackageList(){
		$package = M("package");
		$resl = $package->field("HTYPE,NAME")->select();
		return $resl;
	}

	/**
	* 方法功能：获取风格列表
	* 修改时间：2014年12月15日 Jean
	*/
	function getStyleList(){
		$housestyle = M("housestyle");
		$resl = $housestyle->field("ID,BACKNAME")->select();
		return $resl;
	}

	/**
	* 方法功能：获取色系列表
	* 修改时间：2014年12月15日 Jean
	*/
	function getColorList(){
		$color = M("case_color");
		$resl = $color->field("COLO_ID,NAME")->select();
		return $resl;
	}

	/**
	* 方法功能：获取户型列表
	* 修改时间：2014年12月15日 Jean
	*/
	function getHuList(){
		$housetype = M("housetype");
		$resl = $housetype->field("ID,NAME")->select();
		return $resl;
	}

	/**
	* 方法功能：获取空间类型列表
	* 修改时间：2014年12月15日 Jean
	*/
	function getKongList(){
		$housefunction = M("housefunction");
		$resl = $housefunction->field("ID,BACKNAME")->select();
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
		}elseif($statu==-1){
			$s = "审核不过";
		}
		return $s;
	}



	/**
	* 方法功能：添加案例页
	* 修改时间：2014年12月15日 Jean
	*/
	function add(){
		$this->checkLevel(63);
		$cityList = $this->getCityList();
		$packageList = $this->getPackageList();
		$styleList = $this->getStyleList();
		$colorList = $this->getColorList();
		$huList = $this->getHuList();

		$this->assign("package_list",$packageList);
		$this->assign("city_list",$cityList);
		$this->assign("style_list",$styleList);
		$this->assign("color_list",$colorList);
		$this->assign("hu_list",$huList);
		$this->display();
	}


	/**
	* 方法功能：提交新增案例信息(加修改时间)
	* 修改时间：2014年12月15日 Jean
	*/
	function insert(){
		$_POST["UPDATETIME"] = date("Y-m-d H:i",time());
		$_POST["DID"] = $_POST["orgLookup_dgID"];
		$_POST["CID"] = $_POST["orgLookup_cmID"];
		//继承父类中的insert方法
		parent::insert();
	}


	/**
	* 方法功能：编辑案例信息(加修改时间)
	* 修改时间：2014年12月15日 Jean
	*/
	function update(){
		$_POST["ID"] = $_POST["ID"];
		$_POST["UPDATETIME"] = date("Y-m-d H:i",time());
		$_POST["DID"] = $_POST["orgLookup_dgID"];
		$_POST["CID"] = $_POST["orgLookup_cmID"];
		//继承父类中的insert方法
		parent::update();
	}


	/**
	* 方法功能：案例审核
	* 修改时间：2014年12月15日 Jean
	*/
	function check(){
		$this->checkLevel(66);
		$id = $_REQUEST['id'];
		$case = M("casedecorate");
		$ca_info = $case->field("ID,NAME,status,STATUSDESCRIPTION")->where("ID=$id")->select();
		$this->assign("ca",$ca_info[0]);
		$this->assign("id",$id);
		$this->display();
	}

	/**
	* 方法功能：案例删除
	* 修改时间：2014年12月15日 Jean
	*/
	function del(){
		$this->checkLevel(65);
		$id = $_REQUEST["id"];
		if(!empty($id)){
			$case = M("casedecorate");
			if($case->where("ID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
		
	}

	/**
	* 方法功能：案例效果图列表【录入效果图】
	* 修改时间：2014年12月15日 Jean
	*/
	function add_img(){
		$this->checkLevel(101);
		$id = $_REQUEST['id'];
		$this->assign("cid",$id);
		$this->assign("casename",$this->getCaseName($id));
		$this->assign("img_list",$this->getCaseImgList($id));
		$this->display();
	}

	/**
	* 方法功能：案例效果图添加
	* 修改时间：2014年12月15日 Jean
	*/
	function insert_img(){
		$id = $_REQUEST['cid'];
		$this->assign("id",$id);
		$this->assign("kong_list",$this->getKongList());
		$this->assign("casename",$this->getCaseName($id));
		$this->assign("DID",$this->getCaseDID($id));
		$this->display();
	}

	/**
	* 方法功能：获取案例效果图列表
	* 修改时间：2014年12月15日 Jean
	*/
	function getCaseImgList($id){
		$case_image = M("case_image");
		$image_list = $case_image->where("CID=$id")->select();
		foreach($image_list as $key=>$val){
			$res[$key]['ID'] = $val['ID'];
			$res[$key]['IMAGE'] = ".../".substr($val['IMAGE'],-17);
			$res[$key]['DESCRIPTION'] = $val['DESCRIPTION'];
			$res[$key]['MATERIAL'] = $val['MATERIAL'];
			$res[$key]['PROJECT'] = $val['PROJECT'];
			$res[$key]['HID'] = $this->getKongName($val['HID']);
		}
		return $res;
	}

	/**
	* 方法功能：案例编辑
	* 修改时间：2014年12月15日 Jean
	*/
	function edit(){
		$this->checkLevel(64);
		$id = $_REQUEST['id'];
		if(!empty($id)){
			$case = M("casedecorate");
			$result = $case->where("ID=$id")->select();
			foreach($result as $key=>$val){
				$res[$key]["ID"] = $val["ID"];
				$res[$key]["NAME"] = $val["NAME"];
				$res[$key]["TAG"] = $val["TAG"];
				$res[$key]["cityID"] = $val["cityID"];
				$res[$key]["cityName"] = $this->getCityName($val["cityID"]);
				$res[$key]["PID"] = $val["PID"];
				$res[$key]["PNAME"] = $this->getPackageName($val["PID"]);
				$res[$key]["SID"] = $val["SID"];
				$res[$key]["SNAME"] = $this->getStyleName($val["SID"]);
				$res[$key]["COLO_ID"] = $val["COLO_ID"];
				$res[$key]["CNAME"] = $this->getColorName($val["COLO_ID"]);
				$res[$key]["TYPEID"] = $val["TYPEID"];
				$res[$key]["TYPENAME"] = $this->getHuName($val["TYPEID"]);
				$res[$key]["CID"] = $val["CID"];
				$res[$key]["QUNAME"] = $this->getQuName($val["CID"]);
				$res[$key]["AREA"] = $val["AREA"];
				$res[$key]["DID"] = $val["DID"];
				$res[$key]["DNAME"] = $this->getDesignerName($val["DID"]);
				$res[$key]["IS3D"] = $val["IS3D"];
				$res[$key]["URL3D"] = $val["URL3D"];
				$res[$key]["DESCRIPTION"] = $val["DESCRIPTION"];
				$res[$key]["IMAGE"] = $val["IMAGE"];
			}
			$this->assign("vo",$res[0]);

			$cityList = $this->getCityList();
			$packageList = $this->getPackageList();
			$styleList = $this->getStyleList();
			$colorList = $this->getColorList();
			$huList = $this->getHuList();

			$this->assign("package_list",$packageList);
			$this->assign("city_list",$cityList);
			$this->assign("style_list",$styleList);
			$this->assign("color_list",$colorList);
			$this->assign("hu_list",$huList);
			
			
			$this->display();
		}
	}

	/**
	* 方法功能：案例效果图编辑
	* 修改时间：2014年12月15日 Jean
	*/
	function edit_img(){
		$id = $_REQUEST['id'];
		$cid= $_REQUEST['cid'];
		
		$case_image = M("case_image");
		$img_info = $case_image->where("ID=$id")->select();
		foreach($img_info as $val){
			$res['ID'] = $val['ID'];
			$res['HID'] = $val['HID'];
			$res['IMAGE'] = $val['IMAGE'];
			$res['Kong'] = $this->getKongName($val['HID']);
			$res['DESCRIPTION'] = $val['DESCRIPTION'];
			$res['MATERIAL'] = $val['MATERIAL'];
			$res['PROJECT'] = $val['PROJECT'];
		}

		$this->assign("id",$id);
		$this->assign("cid",$cid);
		$this->assign("kong_list",$this->getKongList());
		$this->assign("casename",$this->getCaseName($cid));
		$this->assign("DID",$this->getCaseDID($cid));
		$this->assign("img_info",$res);

		$this->display();
	}

	/**
	* 方法功能：处理案例效果图编辑
	* 修改时间：2014年12月15日 Jean
	*/
	function update_img(){
		$case = M("case_image");
		$id = $_POST['ID'];
		$data['HID'] = $_POST['HID'];
		$data['DID'] = $_POST['DID'];
		$data['DESCRIPTION'] = $_POST['DESCRIPTION'];
		$data['MATERIAL'] = $_POST['MATERIAL'];
		$data['PROJECT'] = $_POST['PROJECT'];
		$data['IMAGE'] = $_POST['IMAGE'];
		if($case->where("ID=$id")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
	}

	/**
	* 方法功能：插入案例效果图
	* 修改时间：2014年12月15日 Jean
	*/
	function insert_case_img(){
		$case = M("case_image");
		$id = $_POST['id'];
		$data['CID'] = $_POST['CID'];
		$data['DID'] = $_POST['DID'];
		$data['HID'] = $_POST['HID'];
		$data['DESCRIPTION'] = $_POST['DESCRIPTION'];
		$data['MATERIAL'] = $_POST['MATERIAL'];
		$data['PROJECT'] = $_POST['PROJECT'];
		$data['IMAGE'] = $_POST['IMAGE'];

		if($case->add($data)){
			$this->success("添加成功");
		}else{
			$this->error("添加失败");
		}
	}

	/**
	* 方法功能：删除案例效果图
	* 修改时间：2014年12月15日 Jean
	*/
	function del_img(){
		$id = $_GET["id"];
		if(!empty($id)){
			$case_img = M("case_image");
			if($case_img->where("ID=$id")->delete()){
				echo 1;
			}else{
				echo 0;
			}
		}
	}

	/**
	* 方法功能：封装搜索条件
	* 修改时间：2014年12月23日 Jean
	*/
	public function _filter(&$map){
		//判断是否有值
		if(!empty($_REQUEST['ID']) || !empty($_REQUEST['NAME'])){
			$where['ID'] = $_REQUEST['ID'];
			$where['NAME'] = array('like',"%{$_REQUEST['NAME']}%");
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}
	}

	/**
	* 方法功能：老猿挂印回首望-设计师
	* 修改时间：2014年12月16日 Jean
	*/
	function LookupDesigner(){
		$map = $this->_search();
		if(method_exists($this,'_filter')){
			$this->_filter($map);
		}
		//自定义Model类
		$model = M('designer');
		if(!empty($model)){
			$this->_list($model,$map);
		}
		$this->display();

	}


	/**
	* 方法功能：老猿挂印回首望-小区
	* 修改时间：2014年12月16日 Jean
	*/
	function LookupCommunity(){
		$map = $this->_search();
		if(method_exists($this,'_filter')){
			$this->_filter($map);
		}
		//自定义Model类
		$model = M('community');
		if(!empty($model)){
			$this->_list($model,$map);
		}
		$this->display();
	}


	/**
	* 方法功能：案例状态审核提交
	* 修改时间：2014年12月22日 Jean
	*/
	function check_case_status(){
		$id = $_POST["ID"];
		$case = M("casedecorate");
		$data["STATUSDESCRIPTION"] = $_POST["STATUSDESCRIPTION"];
		$data["status"] = $_POST["status"];
		if($case->where("ID=$id")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
	}


}