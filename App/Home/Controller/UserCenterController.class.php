<?php
namespace Home\Controller;
/**
 * 会员中心控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class UserCenterController extends CommonController {
	protected  $user;	

	public function _initialize(){
		parent::_initialize();
	
		if(empty($_SESSION[C('SESSION_USER_KEY')])){
			$url    =   U('Home/User/login');
			$str    = "<script>var url = '{$url}';window.location.href = url;</script>";
			exit($str);
		}
		$user = M('lee_user')->where('id='.$_SESSION[C('SESSION_USER_KEY')]['id'])->find();
		$_SESSION[C('SESSION_USER_KEY')] = $user;
		$this->user = $_SESSION[C('SESSION_USER_KEY')];
		$this->assign('wxuserinfo',$user);
		
	
	}
	
	public function index() {
		$this->display();
	}
	//我的接龙
	public function wdjl(){
		$Model = M('lee_story');
		$info = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where('lee_story_tree.parent=0 and lee_story.uid='.$this->user['id'])->limit(20)->order('create_date desc')->select();;		
		$infonum = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where('lee_story_tree.parent=0 and lee_story.uid='.$this->user['id'])->count();
		$this->assign('info',$info);
		$this->assign('infonum',$infonum);
		$this->display('wdjl');
	}
    function ajaxwdjl(){
    	$p = intval($_GET['p']);
    	$long = 20;
    	$start = ($p+1)*$long;

    	$Model = M('lee_story');
		$kh = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where('lee_story_tree.parent=0 and lee_story.uid='.$this->user['id'])->order('create_date desc')->limit($start,$long)->select();
       $html = '';
        //var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($kh)){
	    	foreach ($kh as $k=>$vo){
	    	$html .= '
            	<div class="lb-list">
                	<table width="0" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="16%"><span class="b1"><a href="/index.php/story/detail?id='.$vo['sid'].'">'.$vo['title'].'</a></span></td>
                        <td width="31%"><span class="b1"></span></td>
                        <td width="27%"><span class="b1"></span></td>
                        <td width="19%" rowspan="2"><span class="b4">'.$vo['create_date'].'</span></td>
                        <td width="4%" rowspan="2"><span class="b3"><a href="/index.php/story/detail?id='.$vo['sid'].'"><img src="images/ht-10.png" /></a></span></td>
                      </tr>
                      <tr>
                        <td><span class="b2">点击：'.$vo['hits'].'</span></td>
                        <td colspan="2"><span class="b2">内容简介：<a href="/index.php/story/detail?id='.$vo['sid'].'">'.strip_tags($vo['content']).'...</a></span></td>
                      </tr>
                    </table>
				</div>
	    			';
	    	}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    	
    }
	//我参与的接龙
	public function wdjlin(){
		$Model = M('lee_story');
		$mystory = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where('lee_story_tree.parent!=0 and lee_story.uid='.$this->user['id'])->limit(20)->order('lee_story.create_date desc')->select();;		
		
		$infonum = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where('lee_story_tree.parent!=0 and lee_story.uid='.$this->user['id'])->count();
		foreach($mystory as $k=>$v){
			$strs = $this->get_parent($v['parent'],$v['sid']);
			$mystory[$k]['urlstr'] = $strs;
		}

		$this->assign('infonum',$infonum);
		$this->assign('mystory',$mystory);
		$this->display('wdjlin');
	}
	private function get_parent($p,$str){
		$Model = M('lee_story_tree');
		$res = $Model->where(array('sid'=>$p))->find();
		if($res['parent'] == 0){
			return $res['sid'].'-'.$str;
		}else{
			return $this->get_parent($res['parent'],$res['sid'].'-'.$str);
		}
	}
    function ajaxwdjlin(){
    	$p = intval($_GET['p']);
    	$long = 20;
    	$start = ($p+1)*$long;

    	$Model = M('lee_story');
		$mystory = $Model->join('left join lee_story_tree ON lee_story.sid = lee_story_tree.sid')->where('lee_story_tree.parent!=0 and lee_story.uid='.$this->user['id'])->limit(20)->order('lee_story.create_date desc')->select();;		
		
		$html = '';
        //var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($mystory)){
	    	foreach ($mystory as $k=>$vo){
			$strs = $this->get_parent($vo['parent'],$vo['sid']);
	    	$html .= '
            	<div class="lb-list">
                	<table width="0" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="16%"><span class="b1"><a href="/index.php/story/detail?id='.$strs.'">'.$vo['title'].'</a></span></td>
                        <td width="31%"><span class="b1"></span></td>
                        <td width="27%"><span class="b1"></span></td>
                        <td width="19%" rowspan="2"><span class="b4">'.$vo['create_date'].'</span></td>
                        <td width="4%" rowspan="2"><span class="b3"><a href="/index.php/story/detail?id='.$strs.'"><img src="images/ht-10.png" /></a></span></td>
                      </tr>
                      <tr>
                        <td><span class="b2">点击：'.$vo['hits'].'</span></td>
                        <td colspan="2"><span class="b2">内容简介：<a href="/index.php/story/detail?id='.$strs.'">'.strip_tags($vo['content']).'...</a></span></td>
                      </tr>
                    </table>
				</div>
	    			';
	    	}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    	
    }
	//我保存的查看历史
	public function history(){
		$Model = M('lee_user_story_history');
		$mystory = $Model->where('uid='.$this->user['id'])->limit(20)->order('create_date desc')->select();;		
		
		$infonum = $Model->where('uid='.$this->user['id'])->count();
		foreach($mystory as $k=>$v){
			$mystory[$k]['urlstr'] = str_replace(',', '-', $v['hstring']);
		}

		$this->assign('infonum',$infonum);
		$this->assign('mystory',$mystory);
		$this->display('history');
	}
	function tx(){
		
		$this->display();
	}
	function txin(){
		$id = $this->user['id'];
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize =3145728 ;// 设置附件上传大小
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  = './Public/Uploads/'; // 设置附件上传目录
		$upload->autoSub = false; //关闭自动生成子目录
		// 上传文件
		$info = $upload->upload();
		//var_dump($info);
		if(!empty($info)){
			$IMAGEdir =__ROOT__."/Public/Uploads/".$info['image_file']['savename'];
			$data['img'] = $IMAGEdir;
		}
		$res = M('lee_user')->where('id='.$id)->save($data);
		//var_dump(M()->getLastSql());
		echo '<script>alert("修改成功！");history.go(-1);"</script>';
	}
    function ajaxwhistory(){
    	$p = intval($_GET['p']);
    	$long = 20;
    	$start = ($p+1)*$long;

    	$Model = M('lee_user_story_history');
    	$mystory = $Model->where('uid='.$this->user['id'])->limit($start,$long)->order('create_date desc')->select();;
    	
		$html = '';
        //var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($mystory)){
	    	foreach ($mystory as $k=>$vo){
	    	$html .= '
            	<div class="lb-list">
                	<table width="0" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="16%"><span class="b1"><a href="/index.php/story/detail?id='.str_replace(',', '-', $vo['hstring']).'">'.$vo['title'].'</a></span></td>
                        <td width="31%"><span class="b1"></span></td>
                        <td width="27%"><span class="b1"></span></td>
                        <td width="19%" rowspan="2"><span class="b4">'.$vo['create_date'].'</span></td>
                        <td width="4%" rowspan="2"><span class="b3"><a href="/index.php/story/detail?id='.str_replace(',', '-', $vo['hstring']).'"><img src="images/ht-10.png" /></a></span></td>
                      </tr>
                      <tr>
                        <td><span class="b2">点击：'.$vo['hits'].'</span></td>
                        <td colspan="2"><span class="b2">内容简介：<a href="/index.php/story/detail?id='.str_replace(',', '-', $vo['hstring']).'">'.strip_tags($vo['description']).'...</a></span></td>
                      </tr>
                    </table>
				</div>
	    			';
	    	}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    	
    }
    function wdzl(){
		$user=$_SESSION[C('SESSION_USER_KEY')];
		$this->assign('wxuserinfo',$user);
		$this->display('wdzl');
    }

    function wdzlin(){
    	$user=$_SESSION[C('SESSION_USER_KEY')];
    	$data['email'] = I('post.email');
    	if(empty($data['email'])){
    		echo '<script>alert("邮箱是必须要填的哦！");history.go(-1);"</script>';
    	}
    	$data['nickname'] = I('post.nickname');
    	$data['sex'] = I('post.sex');
    	$data['mobile'] = I('post.mobile');
    	$data['realname'] = I('post.realname');
    	$data['address'] = I('post.address');
    	$res = M('lee_user')->save($data);
    	echo '<script>alert("修改成功！");history.go(-1);"</script>';
    }
	function savestory(){
		$str = I('get.str',0);
		$strid = explode('-', $str);
		$data['sid'] = $strid[0];
		$data['create_date'] =  date('Y-m-d H:i:s');
		$data['uid'] = $this->user['id'];
		$data['hstring'] = str_replace('-', ',', $str);
		
		$first = M('lee_story')->where('sid='.$strid[0])->find();
		$n = count($strid)-1;
		$end = M('lee_story')->where('sid='.$strid[$n])->find();
		$data['description'] = msubstr(strip_tags($first['content']), 0,10).msubstr(strip_tags($end['content']), 0,10);
		$ishas = M('lee_user_story_history')->where("hstring='".$data['hstring']."'")->find();
		//var_dump($ishas);exit;
		if($ishas){
			$daa['create_date'] =  date('Y-m-d H:i:s');
			$res = M('lee_user_story_history')->where("hstring='".$data['hstring']."'")->save($daa);
		}else{
			$res = M('lee_user_story_history')->add($data);
		}
		echo $res;
	}
	//重置密码
	public function repassword(){
		$user=$_SESSION[C('SESSION_USER_KEY')];
		$this->assign('wxuserinfo',$user);
		$this->display('repassword');
	}
	//重置密码提交
	public function update(){
		$id = $_POST['id'];
		$user=$_SESSION[C('SESSION_USER_KEY')];
		$admin = M("lee_user");
		$password = md5pass($_POST['password']);
		if($password != $user['password']){
    		echo '<script>alert("原密码错误！");history.go(-1);"</script>';exit;
		}
		$newpassword = md5pass($_POST['newpassword']);
		$repassword = md5pass($_POST['repassword']);
		if($newpassword != $repassword){
    		echo '<script>alert("两次密码不一致！");history.go(-1);"</script>';exit;
		}
		$data['password'] = $password;
		$data['updatetime'] = time();
		if($admin->where("id=".$id)->save($data)){
    		echo '<script>alert("重置成功！");history.go(-1);"</script>';exit;
		}else{
    		echo '<script>alert("重置失败！");history.go(-1);"</script>';exit;
		}
	}
	
}