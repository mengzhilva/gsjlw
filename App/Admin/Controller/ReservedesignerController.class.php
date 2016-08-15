<?php
namespace Admin\Controller;

// +----------------------------------------------------------------------
// | * 文件名称：实创家具后台-【预约设计师】功能开发(ReservedesignerController.class.php)
// +----------------------------------------------------------------------
// | * 开发人员：刘东让
// +----------------------------------------------------------------------
// | * 开发时间：2014年12月24日
// +----------------------------------------------------------------------
// | * 修改时间：2014年12月24日
// +----------------------------------------------------------------------

class ReservedesignerController extends CommonController {

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
			$where['NAME'] = array('like',"%{$_POST['NAME']}%");
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
		}
		if(!empty($_POST['CID'])){
			$map['CID'] = $_POST['CID'];
		}
		if(!empty($_POST['DID'])){
			$map['DID'] = $_POST['DID'];
		}
		if(!empty($_POST['STATUS'])){
			$map['STATUS'] = $_POST['STATUS'];
		}
	}


	/*
	* 方法功能：预约设计师列表的展示
	* 修改时间：2014年12月24日 Jean
	*/
    public function index() {
		$this->checkLevel(132);
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
		$map['CID'] = array('in',$this->citylevel);
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        //判断采用自定义的Model类
        $model = D('reservation_designer_n');
        
        if(!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;

    }

	/*
	* 方法功能：预约报名编辑
	* 修改时间：2014年12月24日 Jean
	*/
	function edit(){
		$this->checkLevel(134);
		$id = $_GET["ID"];
		$yd = M("reservation_designer_n");
		$yd_info = $yd->where("ID=$id")->select();
		//var_dump($yd_info);
		$this->assign("vo",$yd_info[0]);
		$this->display();
	}
	
	/*
	* 方法功能：审核预约动作
	* 修改时间：2014年12月24日 Jean
	*/
	function check(){
		$this->checkLevel(135);
		$id = $_GET['ID'];
		$ad = M("reservation_designer_n");
		$ad_info = $ad->field("ID,NAME,STATUS")->where("ID=$id")->select();
		$this->assign("ad",$ad_info[0]);
		$this->display();
	}

	/*
	* 方法功能：添加预约动作
	* 修改时间：2014年12月24日 Jean
	*/
	function insert(){
		$model = M("reservation_designer_n");
		$data['NAME'] = $_POST['NAME'];
		$data['DID'] = $_POST['orgLookup_dgID'];
		$data['COMMUNITY'] = $_POST['orgLookup_cmName'];
		$data['AREA'] = $_POST['AREA'];
		$data['CID'] = $_POST['CID'];
		$data['PHONE'] = $_POST['PHONE'];
		$data['UPDATETIME'] = $_POST['UPDATETIME'];
		if($model->add($data)){
			$this->success("插入成功");
		}else{
			$this->error("插入失败");
		}
	}


	/*
	* 方法功能：编辑预约动作
	* 修改时间：2014年12月24日 Jean
	*/
	function update(){
		$model = M("reservation_designer_n");
		$id = $_POST['ID'];
		$data['NAME'] = $_POST['NAME'];
		$data['DID'] = $_POST['orgLookup_dgID'];
		$data['COMMUNITY'] = $_POST['orgLookup_cmName'];
		$data['AREA'] = $_POST['AREA'];
		$data['CID'] = $_POST['CID'];
		$data['PHONE'] = $_POST['PHONE'];
		$data['UPDATETIME'] = $_POST['UPDATETIME'];
		if($model->where("ID='$id'")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
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


	/*
	* 方法功能：添加预约动作
	* 修改时间：2014年12月19日 Jean
	*/
	function add(){
		$this->checkLevel(133);
		$this->display();
	}

	/*
	* 方法功能：处理预约审核
	* 修改时间：2014年12月19日 Jean
	*/
	function update_check(){
		$id = $_POST["ID"];
		$ad = M("reservation_designer_n");
		$data["STATUS"] = $_POST["STATUS"];
		if($ad->where("ID=$id")->save($data)){
			$this->success("审核成功");
		}else{
			$this->error("审核失败");
		}
	}

	
	/*
	* 方法功能：删除预约动作
	* 修改时间：2014年12月19日 Jean
	*/
	function del(){
		$this->checkLevel(136);
		$id = $_REQUEST["ID"];
		if(!empty($id)){
			$ad = M("reservation_designer_n");
			if($ad->where("ID=$id")->delete()){
				$this->success(L('删除成功'));
			}else{
				$this->error(L("删除失败"));
			}
		}
	}


	



}