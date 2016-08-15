<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class BookController extends CommonController {
	public $keywords ;
	public $description ;
	public function _initialize(){
		$this->keywords = '故事接龙,故事接龙网,最新小说,完本小说,经典小说,小说下载,小说txt下载,全本小说txt下载,无广告';
		$this->description = '故事接龙,故事接龙网,最新小说,完本小说,经典小说,小说下载,小说txt下载,全本小说txt下载,无广告';
		$this->assign('keywords',$this->keywords);
		$this->assign('description',$this->description);
		parent::_initialize();
	}
	public function index(){
		$id = I('get.id', 0);
		$bookmodel = D('Category');
		//var_dump($bookmodel);exit;;
		$info = M('lee_category')->where('id='.$id)->find();
		$list = M('lee_article')->where('category='.$id)->limit(5)->select();
		$cnxh = $bookmodel->cnxh($info);
		//var_dump($info);
		//var_dump($cnxh);
		$this->assign('info',$info);
		$this->assign('list',$list);
		$this->assign('title',$info['name'].','.$this->keywords);
		$this->assign('keywords',$info['name'].'全文阅读,'.$this->keywords);
		$this->assign('description',$info['name'].','.$info['description'].','.$this->description);
		$this->assign('cnxh',$cnxh);
		$this->display();
	}
	public function menulist(){
		$id = I('get.id', 0);
		$where = '';
		if($id == '1'){
			$id = 56;
			$where = 'parent='.$id;
		}else if($id == '4'){
			$where = 'parent in(2360,2814)';
		}else if($id == '3'){
			$where = 'parent in(10,11)';
		}else{
			$where = 'parent='.$id;
		}
		$list = M('lee_category')->where($where.' and img is not null and zhangjie >0')->limit(15)->order('id desc')->select();
		$info = M('lee_category')->where('id='.$id)->find();
		$this->assign('info',$info);
		$this->assign('list',$list);
		$this->assign('id',$id);
		$this->assign('title','精彩小说分类'.','.$this->keywords);
		$this->assign('keywords',$info['name'].'小说分类,'.$this->keywords);
		$this->assign('description',$info['name'].','.$info['description'].','.$this->description);
		$this->display('menulist');
	}
	function menulistmore(){

		$id = intval($_GET['id']);
		$start = intval($_GET['start']);
		$end = 15;
		$from = ($start+1)*15;
		if($id == '1'){
			$id = 56;
			$where = 'parent='.$id;
		}else if($id == '4'){
			$where = 'parent in(2360,2814)';
		}else if($id == '3'){
			$where = 'parent in(10,11)';
		}else{
			$where = 'parent='.$id;
		}
		$booklist = '';
		$booklist = M('lee_category')->where($where.' and img is not null and zhangjie >0')->limit($from,$end)->order('id desc')->select();
		$imgurl = C('IMGURL');
		foreach($booklist as $k=>$vo){
		$html .='<li> <div class="xsimg"><img src="'.$imgurl.$vo['img'].'" /></div>
                           <div class="xsname"><a href="'.U('/book/index?id='.$vo['id'].'').'"><span class="gstitle">'.$vo['name'].' </span></a></div>
                           
                          <div class="xscontent"><a href="'.U('/book/index?id='.$vo['id'].'').'">'.$vo['description'].'...</a></div>
					
					</li>';
		}
		echo $html;
	}
	public function lists(){
		$id = I('get.id', 0);
		$info = M('lee_category')->where('id='.$id)->find();
		$list = M('lee_article')->where('category='.$id)->select();
		$this->assign('info',$info);
		$this->assign('list',$list);
		$this->assign('title',$info['name'].'-目录'.','.$this->keywords);
		$this->assign('keywords',$info['name'].'目录,'.$info['name'].'全文阅读,'.$this->keywords);
		$this->assign('description',$info['name'].','.$info['description'].','.$this->description);
		$this->display('lists');
	}
	public function detail(){
		$id = I('get.id', 0);
		$info = M('lee_article')->where('id='.$id)->find();
		//var_dump($info);exit;
		$book = M('lee_category')->where('id='.$info['category'])->find();
		$details = $this->openfile($book,$info);
		$info['content'] = $details;
		//上下章
		$pre = M('lee_article')->where('category='.$info['category'].' and id<'.$id)->getField('id');
		$next = M('lee_article')->where('category='.$info['category'].' and id>'.$id)->getField('id');
//var_dump($pre);exit;
		M('lee_category')->query('update lee_category set  hits=hits+1 where id='.$info['category']);
		$this->assign('pre',$pre);
		$this->assign('next',$next);
		$this->assign('book',$book);
		$this->assign('info',$info);
		$this->assign('title',$book['name'].'-'.$info['title'].','.$this->keywords);
		$this->assign('keywords',$book['name'].','.$info['title'].','.$book['name'].'全文阅读,'.$this->keywords);
		$this->assign('description',$book['name'].','.$book['description'].','.$this->description);
		$this->display('detail');
		
	}
	public function search(){
		$key = I('get.key', 0);
		$booklist = '';
		if(!empty($key)){
			$booklist = M('lee_category')->where("name like'%".$key."%' or author like'%".$key."%'")->limit(15)->select();
		}
		$this->assign('key',$key);
		$this->assign('booklist',$booklist);
		//推荐 随机
		$maxid = M('lee_category')->count();
		$str = rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid).','.rand(80, $maxid);
		$xstj = M('lee_category')->where('id in ('.$str.') and img is not null')->limit(6)->order('lastupdatetime desc')->select();
		//var_dump($xstj);exit;
		$this->assign('xstj',$xstj);
		$this->assign('title','搜索'.$key.'的结果'.','.$this->keywords);
		$this->display('search');
		
	}
	function searchmore(){

		$key = I('get.key', 0);
		$start = I('get.start', 0);
		$end = 15*$start;
		$from = ($start-1)*15;
		$booklist = '';
		if(!empty($key)){
			$booklist = M('lee_category')->where("name like'%".$key."%' or author like'%".$key."%'")->limit($from,$end)->select();
		}
		$imgurl = C('IMGURL');
		foreach($booklist as $k=>$vo){
		$html .='<li> <div class="xsimg"><img src="'.$imgurl.$vo['img'].'" /></div>
                           <div class="xsname"><a href="'.U('/book/index?id='.$vo['id'].'').'"><span class="gstitle">'.$vo['name'].' </span></a></div>
                           
                          <div class="xscontent"><a href="'.U('/book/index?id='.$vo['id'].'').'">'.$vo['description'].'...</a></div>
					
					</li>';
		}
		echo $html;
	}
	private function openfile($thiscategory,$detail){
		
		$path = '/mnt/www/cms/data/atricle/';
		//var_dump($path.$thiscategory['parent'].'/'.$detail['id']);
		$artic = fopen($path.$thiscategory['parent'].'/'.$detail['category'].'/'.$detail['id'].'.txt','r');
		//var_dump($artic);
		$details = fread($artic,999999);
		return $details;
		//var_dump($details);exit;
		
	}
}