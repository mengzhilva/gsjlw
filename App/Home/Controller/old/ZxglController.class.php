<?php
namespace Home\Controller;
/**
 * 装修攻略控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class ZxglController extends CommonController {

	//右侧内容
	public function _initialize(){
		parent::_initialize();
	}
	//首页
	public function index() {
		$class = M('tbfitmentguideclass')->select();
		$model = M('tbfitmentguide');
		//入门指南
		$where['ClassID'] = 1;
		$where['Status'] = 51;
		$rmzn = $model->where($where)->limit(6)->select();
		//编辑推荐
		
		$wherebj['Status'] = 1;
		$wherebj['InIndex'] = 1;
		$rmzn = $model->where($wherebj)->limit(6)->select();
		//新品主材
		
		//视频攻略
		$where['ClassID'] = 50;
		$xpzc = $model->where($where)->limit(6)->select();

		$this->assign('xpzc', $xpzc);
		$this->assign('rmzn', $rmzn);
		$this->display('index');
	}
	//文章列表页
	public function lists() {
		$class = M('tbfitmentguideclass')->select();
		$model = M('tbfitmentguide');
		//$map = $this->_search();
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		//判断采用自定义的Model类
		$order = 'CityId='.$this->city['ID'].' ';
		if(!empty($model)) {
			$this->_list($model, $where ,$order);
		}
		$this->display('list');
	}
	//视频列表页
	public function videolist() {
		$class = M('tbfitmentguideclass')->select();
		$model = M('tbfitmentguide');
		//$map = $this->_search();
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		//判断采用自定义的Model类
		$order = 'CityId='.$this->city['ID'].' ';
		if(!empty($model)) {
			$this->_list($model, $where ,$order);
		}
		$this->display('videolist');
	}
	//专题列表页
	public function ztlist() {
		$class = M('tbfitmentguideclass')->select();
		$model = M('tbfitmentguide');
		//$map = $this->_search();
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		//判断采用自定义的Model类
		$order = 'CityId='.$this->city['ID'].' ';
		if(!empty($model)) {
			$this->_list($model, $where ,$order);
		}
		$this->display('ztlist');
	}
	//封装搜素条件,自动调用的方法
	public function _filter(){
		$where['CityId'] = $this->city['ID'];
		//搜索条件有值则做封装
		if(!empty($_REQUEST['keyword'])){
			$where['username']  = array('like', "%{$_REQUEST['keyword']}%");
				
		}
		if(!empty($_REQUEST['truename'])){
			$where['truename']  = array('like', "%{$_REQUEST['truename']}%");
				
		}

		return $where;
	}
	public function info() {

		$model = M('tbfitmentguide');
		$id = I('get.id',0);
		$where['ID'] = $id;
		$info = $model->where($where)->select();
		$this->assign('info', $info);
		$this->display('info');
		
	}
}