<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class DetailController extends CommonController {
	
	public function index(){
		$id = I('get.id', 0);
		$info = M('lee_article')->where('');
		
	}
}