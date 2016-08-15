<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class ModelRoomController extends CommonController {
	


	public function index() {
		//幻灯片
		// $where = array();
		// $where['CID'] = $this->city['ID'];
		// $where['SID'] = 1;
		// $hdp = M('slideshow')->where($where)->limit(5)->select();
		// //var_dump($slds);
		// //效果图
		// $wherexg['cityID'] = $this->city['ID'];
		// $wherexg['IS3D'] = 0;
		// $wherexg['status'] = 1;
		// $xg = M("casedecorate")->where($wherexg)->limit(10)->select();
		// //var_dump($xg);
		// //设计师
		// $whered['CID'] = $this->city['ID'];
		// $whered['STATUS'] = 1;
		// $designer = M("designer")->where($whered)->limit(20)->select();
		// //var_dump($designer);
		// $title = "首页";
		// //模板赋值显示
		// $this->assign('title', $title);
		// $this->assign('hdp', $hdp);
		// $this->assign('xg', $xg);
		// $this->assign('designer', $designer);
		$title="全屋定制_实创装饰";
		$this->assign('title', $title);
		$this->display();
		return;
	}
	public function muji(){
		$title="无印实景样板间_实创装饰";
		$this->assign('title', $title);
		$this->display();
	}
	public function ikea(){
		$title="宜家实景样板间_实创装饰";
		$this->assign('title', $title);
		$this->display();
	}
	public function cards(){
		$title="纸牌屋实景样板间_实创装饰";
		$this->assign('title', $title);
		$this->display();
	}
	public function zhongshi(){
		$title="闲庭新中式 实景样板间_实创装饰";
		$this->assign('title', $title);
		$this->display();
	}
	public function zara(){
		$title="ZARA 范儿实景样板间_实创装饰";
		$this->assign('title', $title);
		$this->display();
	}
	
}