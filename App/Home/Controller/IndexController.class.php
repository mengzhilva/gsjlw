<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class IndexController extends CommonController {
	


	public function index() {
		//故事接龙
		$where = array();
		$where['parent'] = 0;
		$Model = M('lee_story');
		//$gsjl = S('gsjl');//memcache 缓存
		if(!$gsjl){
			$gsjl = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->limit(6)->order('update_date desc')->select();
			//S('gsjl',$gsjl,500);
		}
		$this->assign('gsjl', $gsjl);
		
		//小说
		//$xsrd = S('xsrd');//memcache 缓存
		if(!$xsrd){
			//热门点击
			$xsrd = M('lee_category')->where('parent>80 and img is not null and zhangjie >0')->limit(6)->order('hits desc')->select();
			//S('xsrd',$xsrd,5000);
		}
		//$xszx = S('xszx');
		if(!$xszx){
			//最新
			$xszx = M('lee_category')->where('parent>80 and img is not null and zhangjie >0')->limit(6)->order('id desc')->select();
			//S('xszx',$xszx,5000);
		}
		//$xstj = S('xstj');
		if(!$xstj){
			//推荐 随机
			$maxid = M('lee_category')->count();
			$str = rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid);
			$xstj = M('lee_category')->where('id in ('.$str.') and img is not null and zhangjie >0')->limit(6)->order('lastupdatetime desc')->select();
			//var_dump($xstj);exit;
			//S('xstj',$xstj,5000);
		}
		$this->assign('xsrd', $xsrd);
		$this->assign('xszx', $xszx);
		$this->assign('xstj', $xstj);
		$title="首页_故事接龙，故事接龙网";
		$this->assign('title', $title);
		$this->display();
		return;
	}
	function menu(){
		$menu = M('lee_category')->where("parent=0 and lang='zh_cn' and name!='故事接龙'")->select();
		$this->assign('menu', $menu);
		$this->display();
		
	}
	function memca(){
		//phpinfo();exit;
		$values = S('b');
		var_dump($values);
		if(!$values){
			$Model = M('lee_story');
			$value = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->limit(6)->order('update_date desc')->select();
			S('b',$value);
		}
		//var_dump($a);
		var_dump($values);
	}
}