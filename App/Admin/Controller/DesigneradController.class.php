<?php
namespace Admin\Controller;

class DesigneradController extends CommonController {
	//封装搜素条件,自动调用的方法
		public function _filter(&$map){
				//搜索条件有值则做封装
				if(!empty($_REQUEST['CID'])){
					$where['CID']  = $_REQUEST['CID'];
				}
				if(!empty($_REQUEST['NAME'])){
					$where['NAME'] = array('like', "%{$_REQUEST['NAME']}%");
				}
				return $where;
		}
		public function __construct(){
			parent::__construct();
			$this->designerad = M('designer_ad');
			//查询城市
			$this->citylevel = $_SESSION['scadminuser']['cid'];
			$city = M("city");
			$cityname = $city->where('id in ('.$this->citylevel.')')->select();
			$this->assign('cityname',$cityname);
			//获取设计师标签
			$Designer = M("Designer");
			$Designername = $Designer->where('STATUS=1')->select();
			$this->assign('Designername',$Designername);
		}
		public function index() {
			//权限管理
				$this->checkLevel(11);
				//列表过滤器，生成查询Map对象
				$where = '';
				if(method_exists($this, '_filter')) {
					$where = $this->_filter();
				}
				//判断采用自定义的Model类
				$model = $this->designerad;
				if(!empty($model)) {
					$this->_list($model, $where);
				}
				// $this->assign('vo',$Designad);
				$this->display('index');
			 
		}
	public function add(){
		$this->checkLevel(12);
		$this->display("add");
	}

	public function insert(){
		$designer = M("designer_ad");
		$data['CID'] = $_POST['CID'];
		$data['NAME'] = $_POST['NAME'];
		$data['RESUME'] = $_POST['RESUME'];
		$data['RANK'] = $_POST['RANK'];
		$data['STAR'] = $_POST['STAR'];
		$data['STATUS'] = $_POST['STATUS'];
		$data['FOCUS'] = 0;//默认为不是焦点
		$data['IMAGE'] = $_POST['IMAGE'];
		$data['UPDATETIME'] = date('Y-m-d H:i:s');

		if($designer->add($data)){
			$this->success("添加成功!");
		}else{
			$this->error("添加失败!");
		}
	}
	public function edit(){
		$this->checkLevel(13);
		$id=$_GET['ID'];
		$designer = M("designer_ad");
		$designerad=$designer->where('ID='.$id)->find();
		$this->assign('vo',$designerad);
		$this->display('edit');
	}
	public function update(){
		$id = $_POST['ID'];
		$designer = M("designer_ad");
		$data['CID'] = $_POST['CID'];
		$data['NAME'] = $_POST['NAME'];
		$data['RESUME'] = $_POST['RESUME'];
		$data['RANK'] = $_POST['RANK'];
		$data['STAR'] = $_POST['STAR'];
		$data['STATUS'] = $_POST['STATUS'];
		$data['IMAGE'] = $_POST['IMAGE'];
		$data['UPDATETIME'] = date('Y-m-d H:i:s');
		if($designer->where("ID=$id")->save($data)){
			$this->success("修改成功");
		}else{
			$this->error("修改失败");
		}
	}
	public function del() {
		$this->checkLevel(14);
		//删除指定记录
		$model = M('designer_ad');
		$id = $_REQUEST['ids'];print_r($$_REQUEST['ids']);
		if (isset($id) && is_string($id)) {
				//批量删除
				$condition = array('ID' => array('in', $id));
				if (false !== $model->where($condition)->delete()) {
						$this->success(L('删除成功'));
				} else {
						$this->error(L('删除失败'));
				}
		} else if (is_array($id)){
				//批量删除
				$condition = array('ID'=> array('in', explode(',', $id)));
				if (false !== $model->where($condition)->delete()) {
						$this->success(L('删除成功'));
				} else {
						$this->error(L('删除失败'));
				}
		}else {
				$this->error('非法操作');
		}
	}
	//启用焦点
	public function focus(){
		$this->checkLevel(15);
		$id=$_REQUEST['ID'];
		$FOCUS=$_REQUEST['FOCUS'];

		$this->assign('id',$id);
		$this->assign('FOCUS',$FOCUS);
		$this->display('focus');
	}
	public function refocus(){
		$designer_ad = M("Designer_ad");
		$id=$_REQUEST['ID'];
		$FOCUS=$_REQUEST['FOCUS'];

		if($FOCUS==1){
			$result=$designer_ad->where('ID='.$id)->setField('FOCUS','1');
		}elseif($FOCUS==0){
			$result=$designer_ad->where('ID='.$id)->setField('FOCUS','0');
		}
	 
		if (false !== $result) {
					$this->success(L('设置成功'));
				} else {
					$this->error(L('设置失败'));
				}
	}

}