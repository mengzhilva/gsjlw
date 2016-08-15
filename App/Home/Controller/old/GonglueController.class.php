<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 装修攻略控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class GonglueController extends GonglueCommonController {

	//右侧内容
	public function _initialize(){
		parent::_initialize();
	}
	//首页
	public function index() {
		//echo "a";
		//$class = M('tbfitmentguideclass')->select();
		//$model = M('tbfitmentguide');
		//读取分类
		$this->ShowClass();
		//入门指南
		$guide = M('tbfitmentguide');
		$where['ClassName'] = '入门指南';
		$where['Status'] = 1;
		$rmzn = $guide->where($where)->limit(6)->select();
		
		//编辑推荐
		$wherebj['Status'] = 1;
		$wherebj['InIndex'] = 1;
		$bjtj = $guide->where($wherebj)->limit(6)->select();
		
		//新品主材
		$wherexp['ClassName'] = '新品主材';
		$wherexp['Status'] = 1;
		$xpzc = $guide->where($wherexp)->limit(6)->select();

		//视频学装修
		$wheresp['ClassName'] = '视频学装修';
		$wheresp['Status'] = 1;
		$spxzx = $guide->where($wheresp)->limit(3)->select();		
		$title='装修攻略';
		$this->assign('title', $title);
		$this->assign('rmzn', $rmzn);
		$this->assign('bjtj', $bjtj);
		$this->assign('xpzc', $xpzc);
		$this->assign('spxzx', $spxzx);
		$this->display('index');
	}
	//显示分类
	public function ShowClass(){
		$model = M('tbfitmentguideclass');
		$item = $model -> where('ParentID = 0') -> order('OrderID desc, ClassID asc') -> limit(3) -> select();
		if($item){
			$model_2 = M('tbfitmentguideclass');
			for($i = 0; $i < count($item); $i++){
				$id = $item[$i]['ClassID'];
				$item[$i][$i+1] = $model_2 -> where('ParentID = ' . $id) -> order('OrderID desc, ClassID asc') -> limit(6) -> select();     
			}     
			$this -> assign('item', $item); 
		}
	}
	//文章列表页
	public function lists() {
		//$class = M('tbfitmentguideclass')->select();
		$class = M('tbfitmentguideclass');
		$model = M('tbfitmentguide');	
		$ClassID = I('get.classid', 0);
			
		//显示分类
		$this->ShowClass();

		//显示分类名称
		//echo $ClassID;
		//die();
		$ClassName = $class->where('ClassID='.$ClassID)->getField('ClassName');
		$this->assign('ClassName', $ClassName);
		//$map = $this->_search();
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		//判断采用自定义的Model类
		//$order = 'CityId='.$this->city['ID'].' ';
		$order = 'UpdateTime ';
		$where['ClassID'] = $ClassID;
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 15);
			//dump(lists);
		}
		$title=$ClassName . '_装修攻略';
		$this->assign('title', $title);
		$this->display('list');
	}
	//视频列表页
	public function videolist() {
		//$class = M('tbfitmentguideclass')->select();
		$model = M('tbfitmentguide');
		//$map = $this->_search();
		// if(method_exists($this, '_filter')) {
		// 	$where = $this->_filter();
		// }
		//判断采用自定义的Model类
		$order = 'id';
		$where['Status'] = 1;
		$where['ClassName'] = '视频学装修';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 15);
		}
		$title='视频学装修_装修攻略';
		$this->assign('title', $title);
		$this->display('video-list');
	}
	//视频详情页
	public function videoshow() {
		$model = M('tbfitmentguide');
		$id = I('get.id',0);
		$where['ID'] = $id;
		$show = $model->where($where)->find();
		$prev = $model->where('ID < '.$id)->order('id desc')->find();
		$next = $model->where('ID > '.$id)->order('id asc')->find();
		$this->assign('show', $show);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$title=$show['Title'] . '_装修攻略';
		$this->assign('title', $title);
		$this->display('video-show');
	}
	//专题列表页
	public function zhuanti() {
		//$class = M('tbfitmentguideclass')->select();
		$model = M('tbfitmentguide');
		//$map = $this->_search();
		// if(method_exists($this, '_filter')) {
		// 	$where = $this->_filter();
		// }
		//判断采用自定义的Model类
		$order = 'id';
		$where['Status'] = 1;
		$where['ClassName'] = '专题';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 6);
		}
		$title='专题_装修攻略';
		$this->assign('title', $title);
		$this->display('zhuanti');
	}
	//封装搜素条件,自动调用的方法
	public function _filter(){
		//$where['CityId'] = $this->city['ID'];
		//$where['ID'] = array('LT','170');
		$where['Status'] = 1;
		//搜索条件有值则做封装
		if(is_numeric($_REQUEST['ClassID'])){
			$where['ClassID']  = $_REQUEST['ClassID'];
				
		}
		return $where;
	}
	public function info() {

		$model = M('tbfitmentguide');
		$id = I('get.id',0);
		$where['ID'] = $id;
		$show = $model->where($where)->find();
		$prev = $model->where('ID < '.$id)->order('id desc')->find();
		$next = $model->where('ID > '.$id)->order('id asc')->find();
		$this->assign('show', $show);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$title=$show['Title'] . '_装修攻略';
		$this->assign('title', $title);		
		$this->display('show');
	}
	//右侧报名处理
	public function RightOrder(){
		$info="提交失败";
		$status="n";
	    $model = M('specialistorder'); 
	    if (IS_POST) {
    		$data['TrueName']=I('post.username');
    		$data['Telephone']=I('post.telephone');
    		$data['Community']=I('post.community');
    		$data['Area']=I('post.area');
    		$data['SpecialistType']=I('post.SpecialistType');
		    $result = $model->data($data)->add(); 
		    //dump($result);
			//			    echo $model->getLastSql();
		    //die();  
		    if($result){
		    // 如果主键是自动增长型 成功后返回值就是最新插入的值
		    	//$insertId = $result;
				$info="提交成功";
				$status="y";	

		    // }else{
			   //  echo $model->getDbError();
		    // die();  
		    }	
	    }		
	    $cdata['info'] = $info;
	    $cdata['status'] = $status;
	    $this->ajaxReturn($cdata,'JSON');
	}
}