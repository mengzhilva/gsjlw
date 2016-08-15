<?php
namespace Home\Controller;
/**
 * 首页控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class StoryController extends CommonController {
	
	public function index(){
		$id = I('get.id', 0);
		$info = M('lee_category')->where('id='.$id)->find();
		$list = M('lee_article')->where('category='.$id)->limit(5)->select();
		$cnxh = M('lee_category')->where('parent='.$info['parent'].' and img is not null')->limit(6)->select();
		$this->assign('info',$info);
		$this->assign('list',$list);
		$this->assign('title',$info['name']);
		$this->assign('cnxh',$cnxh);
		$this->display();
	}
	public function lists(){
		$key = I('get.key', 0);
		$where = 'lee_story_tree.parent = 0';
		if(!empty($key)){
			$where = "lee_story_tree.title like '%".$key."%'";
    		$purl .= '/key/'.$key;
		}
		$list = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->limit(20)->order('update_date desc')->select();
		//$this->assign('info',$info);
		$this->assign('purl',$purl);
		$this->assign('list',$list);
		$this->assign('title','故事接龙');
		$this->display('lists');
	}
    function ajaxlists(){
    	$p = intval($_GET['p']);
    	$key = check_input($_GET['key']);
    	$long = 20;
    	$start = ($p+1)*$long;
		if(!empty($key)){
			$where = "lee_story_tree.title like '%".$key."%'";
    		$purl .= '/key/'.$key;
		}
		$where = 'lee_story_tree.parent = 0';

    	$mystory = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->limit($start,$long)->order('update_date desc')->select();
    	 
		$html = '';
        //var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($mystory)){
	    	foreach ($mystory as $k=>$vo){
	    	$html .= '
            	
                           <a href="/index.php/story/detail?id='.$vo['sid'].'"><span class="gstitle">'.$vo['title'].' </span></a>
                           <div class="gscontent"><a href="/index.php/story/detail?id='.$vo['sid'].'">'.msubstr($vo['content'],0,60).'...</a></div>
                           
	    			';
	    	}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    	
    }
    function hots(){
		$key = I('get.key', 0);
		if(!empty($key)){
			$where = "title like '%".$key."%'";
    		$purl .= '/key/'.$key;
		}
		$list = M('lee_user_story_history')->where($where)->limit(20)->order('create_date desc')->select();
		//$this->assign('info',$info);
		$this->assign('purl',$purl);
		$this->assign('list',$list);
		$this->assign('title','热门接龙');
		$this->display('hots');
    	
    }
    function ajaxhots(){
    	$p = intval($_GET['p']);
    	$key = check_input($_GET['key']);
    	$long = 20;
    	$start = ($p+1)*$long;
		if(!empty($key)){
			$where = "title like '%".$key."%'";
    		$purl .= '/key/'.$key;
		}

    	$Model = M('lee_user_story_history');
    	$mystory = $Model->where($where)->limit($start,$long)->order('create_date desc')->select();;
    	
		$html = '';
        //var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($mystory)){
	    	foreach ($mystory as $k=>$vo){
	    	$html .= '
            	
                           <a href="/index.php/story/detail?id='.$vo['sid'].'"><span class="gstitle">'.$vo['title'].' </span></a>
                           <div class="gscontent"><a href="/index.php/story/detail?id='.$vo['sid'].'">'.$vo['description'].'...</a></div>
                           
	    			';
	    	}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    	
    }
	private function get_randstory($s,$str){
			$res = M('lee_story_tree')->where(array('parent'=>$s))->select();
			$resnum = M('lee_story_tree')->where(array('parent'=>$s))->count();
		
		if(empty($res)){
			return $str;
		}else{
			$key = rand(0,($resnum-1));
			return $this->get_randstory($res[$key]['sid'],$str.','.$res[$key]['sid']);
		}
	}
	public function detail(){
		$id = I('get.id', 0);
		$sonid = I('get.sonid',0);
		$zid = $id;
		$id = str_replace('-',',',$id);
		$sj = I('get.sj', 0);
		if($sj == 1){//如果是随机选
			$res = M('lee_story_tree')->where(array('parent'=>$id))->select();
			if(!empty($res)){
				$resnum = M('lee_story_tree')->where(array('parent'=>$id))->count();
				$key = rand(0,($resnum-1));
				$strs = $this->get_randstory($res[$key]['sid'],$res[$key]['sid']);
				$strs = $id.','.$strs;
			}else{
				$strs = $id;
			}
			$id = $strs;
			//var_dump($strs);exit;
		}
		$infos = M('lee_story')->where('sid in ('.$id.')')->select();
		$str = '';
		foreach ($infos as $k=>$v){
			if($k == 0){
				$str = $v['sid'];
				$zid = $v['sid'];
			}else{
				$str .= '-'.$v['sid'];
			}
			$infos[$k]['str'] = $str;

			//评论总数
			$commentnum = M('lee_reply')->where('story_id='.$v['sid'])->count();
			$infos[$k]['commentnum'] = $commentnum+0;
		}
		//var_dump($info);exit;parent
		$where['parent'] = $infos[count($infos)-1]['sid'];
		$list = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->order('update_date desc')->select();
		//var_dump($list);
		$this->assign('list',$list);
		$this->assign('id',$zid);
		$this->assign('info',$infos);
		$this->assign('str',$str);
		$this->assign('infos',$infos[0]);
		$this->assign('title','故事接龙'.$infos[0]['title']);
		$this->display('detail');
		
	}
	function add(){
		$fid = I('get.fid',0);
		$sid = I('get.sid',0);
		$str = I('get.str',0);
		if(!empty($fid)){
			$where['lee_story.sid'] = $fid;
			$info = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->order('update_date desc')->find();
		}
		$this->assign('info',$info);
		//总的标题信息
		if(!empty($sid)){
			$where['lee_story.sid'] = $sid;
			$finfo = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->order('update_date desc')->find();
		}
		$content = I('post.content',0);
		if(!empty($content)){
			$local = I('get.local',0);
			$this->assign('content',$content);
			//if($local == 'title'){
				
			//}
		}
		$this->assign('finfo',$finfo);
		$this->assign('sid',$sid);
		$this->assign('fid',$fid);
		$this->assign('str',$str);
		$this->display('add');
	}
	function comment(){
		$sid = I('get.id',0);
		$str = I('get.str',0);
		$this->assign('sid',$sid);
		$this->assign('str',$str);
		//总的标题信息
		if(!empty($sid)){
			$where['lee_story.sid'] = $sid;
			$finfo = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->order('update_date desc')->find();
			$wheres['story_id'] = $sid;
			$comments = M('lee_reply')->where($wheres)->order('create_time asc')->select();
		}
		//var_dump($finfo);
		$title = '';
		if(!empty($finfo['title'])){
			$title = $finfo['title'];
		}else{
			$title = strip_tags(msubstr($finfo['content'],0,20,'utf-8'));
		}
		$this->assign('title',$title);
		$this->assign('finfo',$finfo);
		$this->assign('comments',$comments);
		$this->display('comment');
	}
	function commentinsert(){

		$content = I('post.content',0);
		$str = I('post.str',0);
		$sid = I('post.sid',0);
		$user = $_SESSION[C('SESSION_USER_KEY')];
		$uid = $user['id'];
		if(empty($uid)){
			$uid = 0;
		}
		$data['user_id'] = $uid;
		$data['story_id'] = $sid;
		$data['ip'] = $this->getip();
		$data['content'] = $content;
		$data['create_time'] = date('Y-m-d H:i:s');
		M('lee_reply')->add($data);
		echo '<script>alert("添加成功！");document.location.href="/index.php/story/detail/id/'.$str.'"</script>';
		
	}
	function insert(){
		$user = $_SESSION[C('SESSION_USER_KEY')];
		$uid = $user['id'];
		if(empty($uid)){
			$uid = 0;
		}
		$str = I('post.str',0);
		$zid = I('post.zid',0);
		$parent = I('post.parent',0);
		$category = I('post.category',0);
		$level = I('post.level',0);
		$title = I('post.title',0);
		$content = I('post.content',0);
		if(empty($parent)){
			$parent = 0;
		}
		
            $story['title'] = $title;
            $story['category_id'] = 25;//$data['category'];
            $story['content'] = $content;
            $story['img'] = 0;
            $story['uid'] = $uid;//不确定是否必须登录
			$story['create_date'] = date('Y-m-d H:i:s');
			$story['update_date'] = date('Y-m-d H:i:s');
			$iszigs = $parent>0?1:0;
            $sid = M('lee_story')->add($story);
            $data['sid'] = $sid;
			if($parent>0){
				$view = M('lee_story_tree')->where(array('sid'=>$parent))->find();
				$data['lft'] = $view['rht'];
				M('lee_story_tree')->query('update lee_story_tree set rht=rht+2 where rht>='.$data['lft']);
				M('lee_story_tree')->query('update lee_story_tree set lft=lft+2 where lft>='.$data['lft']);
                //var_dump($data);exit;
			}else{
				$maxnum = M('lee_story_tree')->count();
				$data['lft'] = $maxnum*2+1;
			}
			$data['rht'] = $data['lft']+1; 
			$data['parent'] = $parent;
			$data['level'] = $level+1; 
			$data['parent'] = $parent;
			$data['zid'] = $zid;
			M('lee_story_tree')->add($data);
			if($iszigs == 1){
				$fstory['update_date'] = date('Y-m-d H:i:s');
            	M('lee_story')->where('sid='.$parent)->save($fstory);
			}
		$str = $str.'-'.$sid;
		echo '<script>alert("添加成功！");document.location.href="/index.php/story/detail/id/'.$str.'"</script>';
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
		$this->assign('title','搜索'.$key.'的结果');
		$this->display('search');
		
	}
	function sonstory(){

		$zid = I('get.zid', 0);//总的id
		$sid = I('get.sid', 0);
		$str = I('get.str', 0);
		$wids = I('get.wids', 0);
		if(!empty($sid)){
			$info = M('lee_story')->where("sid=".$sid)->find();
			$where['parent'] = $sid;
			$list = M('lee_story')->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where($where)->order('update_date desc')->select();
				
		}
		//评论总数
		$comments = M('lee_reply')->where('story_id='.$sid)->count();
		$return['info'] = '  
    <section class="sc-floor-row section-1 clearfix">
        <h3 class="clearfix">
            <p class="chi-ti detailtitle">'.$info['title'].'</p>
        </h3>
    </section>
    <section class="clearfix book">
        <div class="index-classify bookdetail">'.$info['content'].'
				<div class="zigus">
				<a href="/index.php/story/add/fid/'.$sid.'?sid='.$zid.'&str='.$str.'">增加子故事</a>
				<a href="/index.php/story/detail/id/'.$str.'">切换子故事</a>
						<a href="/index.php/story/comment/id/'.$sid.'/str/'.$str.'">评论('.($comments+0).')</a>
						</div></div>';
		$return['html'] = '<li style="width:'.$wids.'px"><div style="font-size:16px;text-align:center;width:'.$wids.'px;height:50px;line-height:50px;"><a href="" >没有人接龙了，我来接</a></div></li>';
		if(!empty($list)){
			$return['html'] = '';
			foreach($list as $k=>$vo){
					$return['html'] .='<li style="width:'.$wids.'px"> 
	                           <div class="xsname"><a href="javascript:addson('.$vo['sid'].');"><span class="gstitle">'.$vo['title'].' </span></a></div>
	                           
	                          <div class="xscontent"><a href="javascript:addson('.$vo['sid'].');">'.$vo['content'].'...</a></div>
						
						</li>';
			}
		}
		echo json_encode($return);
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