<?php
namespace Home\Controller;
/**
 * 关于我们控制器
 * 开发人员：吕定国
 * 开发时间：2014.12.08
 * 修改时间：2015.1.12
 */
class AboutController extends CommonController {



	public function index() {
		$title='企业简介_关于实创';
		$this->assign('title', $title);
		$this->display('about-qyjj');
	}

	// public function newslist() {
	// 	$news = M('tbnews');
	// 	$where['Statusdescription'] = 1;
	// 	$newslist = $news->where($where)->select();
	// }
	// public function newsinfo() {

	// 	$news = M('tbnews');
	// 	$id = I('get.id',0);
	// 	$where['ID'] = $id;
	// 	$newsinfo = $news->where($where)->select();
	// 	var_dump($newsinfo);
		
	// }
	//企业简介
	public function sitrust(){
		$title='企业简介_关于实创';
		$this->assign('title', $title);
		$this->display('about-qyjj');
	}
	//品牌历程
	public function history(){
		$title='品牌历程_关于实创';
		$this->assign('title', $title);
		$this->display('about-pplc');
	}
	//品牌荣誉
	public function brand(){
		$title='品牌荣誉_关于实创';
		$this->assign('title', $title);
		$this->display('about-ppry');
	}
	//企业创始人
	public function sunwei(){
		$title='企业创始人_关于实创';
		$this->assign('title', $title);
		$this->display('about-qycsr');
	}
	//企业文化
	public function culture(){
		$title='企业文化_关于实创';
		$this->assign('title', $title);
		$this->display('about-qywh');
	}
	//法律声明
	public function legal(){
		$title='法律声明_关于实创';
		$this->assign('title', $title);
		$this->display('about-flsm');
	}
	//社会责任
	public function social(){
		$title='社会责任_关于实创';
		$this->assign('title', $title);
		$this->display('about-shzr');
	}
	//新闻中心
	public function news(){
		$model=M('tbnews');
		$order = 'ID';
		$where['Passed']=1;
		$where['ClassName']='实创新闻';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 21);
		}	
		$title='新闻中心_关于实创';
		$this->assign('title', $title);	
		$this->display('about-news');
	}
	//新闻详情页	
	public function newsinfo() {

		$model = M('tbnews');
		$id = I('get.id',0);
		$where['ID'] = $id;
		$where['Passed']=1;
		$where['ClassName']='实创新闻';		
		$show = $model->where($where)->find();
		$prev = $model->where("ClassName = '实创新闻' and Passed = 1 and ID < ".$id)->order('id desc')->find();
		$next = $model->where("ClassName = '实创新闻' and Passed = 1 and ID > ".$id)->order('id asc')->find();
		$this->assign('show', $show);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$title=$show['Title'] . '_新闻中心_关于实创';
		$this->assign('title', $title);
		$this->display('about-news-show');
	}
	//校园招聘
	public function join_campus(){
		$model=M('tbjob');
		$order = 'id';
		//$where['Passed']=1;
		$where['ClassName']='校园招聘';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 15);
		}	
		$title='校园招聘_加入我们';
		$this->assign('title', $title);		
		$this->display('joinus-xyzp');
	}
	//社会招聘
	public function join_social(){
		$model=M('tbjob');
		$order = 'id';
		//$where['Passed']=1;
		$where['ClassName']='社会招聘';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 15);
		}		
		$title='社会招聘_加入我们';
		$this->assign('title', $title);		
		$this->display('joinus-shzp');
	}
	//招聘详情页
	public function jobinfo() {

		$model = M('tbjob');
		$id = I('get.id',0);
		$where['id'] = $id;	
		$show = $model->where($where)->find();
		$prev = $model->where("id < ".$id)->order('id desc')->find();
		$next = $model->where("id > ".$id)->order('id asc')->find();
		$this->assign('show', $show);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$title=$show['Title'] . '_实创招聘_加入我们';
		$this->assign('title', $title);	
		$this->display('joinus-shzp-show');
	}
	//员工风采
	public function life(){
		$model=M('tbnews');
		$order = 'ID';
		$where['Passed']=1;
		$where['ClassName']='员工风采';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 8);
		}		
		//员工风采-成长实创
		$where['InIndex']=1;
		$czsc=$model->where($where)->limit(3)->select();
		$this->assign('czsc', $czsc);
		$title='员工风采_加入我们';
		$this->assign('title', $title);	
		$this->display('joinus-ygfc');
	}
	//员工风采详情页	
	public function lifeinfo() {

		$model = M('tbnews');
		$id = I('get.id',0);
		$where['ID'] = $id;
		$where['Passed']=1;
		$where['ClassName']='员工风采';		
		$show = $model->where($where)->find();
		$prev = $model->where("ClassName = '员工风采' and Passed = 1 and ID < ".$id)->order('id desc')->find();
		$next = $model->where("ClassName = '员工风采' and Passed = 1 and ID > ".$id)->order('id asc')->find();
		$this->assign('show', $show);
		$this->assign('prev', $prev);
		$this->assign('next', $next);
		$title=$show['Title'] . '_员工风采_加入我们';
		$this->assign('title', $title);	
		$this->display('joinus-ygfc-show');
	}
	//薪酬福利
	public function welfare(){
		$title='薪酬福利_加入我们';
		$this->assign('title', $title);	
		$this->display('joinus-xcfl');
	}
	//加入我们
	public function joinus(){
		$model=M('tbjob');
		$order = 'id';
		//$where['Passed']=1;
		$where['ClassName']='社会招聘';
		if(!empty($model)) {
			$this->_list($model, $where ,$order, false, 15);
		}		
		$title='加入我们';
		$this->assign('title', $title);			
		$this->display('joinus');
	}	
}