<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 装修攻略控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class GonglueCommonController extends CommonController {


	public function _initialize(){
		parent::_initialize();
		$this->glright();
	}
	//右侧内容
	public function glright() {
		//echo "a";
		//$class = M('tbfitmentguideclass')->select();
		//$model = M('tbfitmentguide');
		//读取分类

		//最新文章
		$guide = M('tbfitmentguide');
		$where['ClassName'] = array('NEQ','专题');
		$where['Status'] = 1;
		$zxfz = $guide->where($where)->order('id desc')->limit(10)->select();
		
		//专题
		$wherezt['Status'] = 1;
		$wherezt['ClassName'] = array('EQ','专题');
		$zt = $guide->where($wherezt)->order('id desc')->limit(3)->select();
		
		$this->assign('zxfz', $zxfz);
		$this->assign('zt', $zt);
		//$this->display('right');
	}

}