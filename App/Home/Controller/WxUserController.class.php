<?php
namespace Home\Controller;
/**
 * 会员登录注册等控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class WxUserController extends WxCommonController {
    
    public function index(){
    	$info = $_SESSION['wxpromoter'];
    	//案例
    	$al = M('casedecorate')->where("(UID='".$info['Id']."' or Uid=2673) and status=1")->limit(7)->order('UPDATETIME desc')->select();
    	$alimg = M('case_image')->where("CID='".$al[0]['ID']."'")->limit(2)->select();
    	//文章
    	//荣誉
    	$wzry = M('tbfitmentguide')->where(" InIndex=1 and ClassID=179 and Status=1 ")->limit(5)->order('UpdateTime desc')->select();
		//热门文章等 最新添加的分类里的文章
		$newclass = M('tbfitmentguideclass')->where(" ClassID!=179 and ParentID=0")->limit(3)->order('ClassID desc')->select();
    	$rm =  M('tbfitmentguide')->where(" InIndex=1 and ClassID!=179 and Status=1 and Hot=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz1 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[0]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz2 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[1]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz3 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[2]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();

    	$this->assign('newclass',$newclass);
    	$this->assign('rm',$rm);
    	$this->assign('wz1',$wz1);
    	$this->assign('wz2',$wz2);
    	$this->assign('wz3',$wz3);

    	//分享链接
        //转发时的信息
        $zhuanfainfo = $_SESSION['wxpromoter'];
        $ispromoters = M('tbpromoter')->where(array('wxopenid'=>$_SESSION['wxinfo']['openid']))->find();
        if(!empty($ispromoters)){
            $zhuanfainfo = $ispromoters;
        }
    	$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/index/topid/'.$zhuanfainfo['Id'];
    	$returnurl = urlencode($returnurl);
    	$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
    	
    	$this->assign('fxurl',$fxurl);
    	
    	//活动广告
    	$hdggd = M('activity')->where('pid=73 and STATUS=1')->find();
    	$hdggx = M('activity')->where('pid=74 and STATUS=1')->select();
    	$this->assign('hdggd',$hdggd);
    	$this->assign('hdggx',$hdggx);
    	
    	$this->assign('al',$al);
    	$this->assign('alimg',$alimg);
		$this->assign('wzry',$wzry);
		$this->assign('qudao','店铺首页');
		$this->assign('utm_term',$info['PromoterName'].'的店铺');
		
    	$this->assign('title',$info['PromoterName'].'的店铺');
    	$this->assign('description',$info['PromoterName'].'的店铺');
    	$this->assign('searchkey',$_SESSION['searchkey']);
    	$this->assign('isxiala',1);
    	$this->display();
    }
	//个人名片
	function card(){

		$this->display();
	}
    function tuiguang(){
    	$key = I('get.key','');
    	$type = I('get.type','');
    	if(!empty($key)){
    		$where = " and Title like'%".$key."%'";
    		$purl .= '/key/'.$key;
    	}
    	if(!empty($type)){
    		$where = " and ClassID =$type";
    		$purl .= '/type/'.$type;
    	}
    	$rm =  M('tbfitmentguide')->where("  Status=1 and Hot=1".$where)->limit(6)->order('UpdateTime desc')->select();
    	$newclass = M('tbfitmentguideclass')->where("  ParentID=0")->order('ClassID desc')->select();

        //分享链接
        //转发时的信息
        $zhuanfainfo = $_SESSION['wxpromoter'];
        $ispromoters = M('tbpromoter')->where(array('wxopenid'=>$_SESSION['wxinfo']['openid']))->find();
        if(!empty($ispromoters)){
            $zhuanfainfo = $ispromoters;
        }
        $returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/allist/topid/'.$zhuanfainfo['Id'];
        $returnurl = urlencode($returnurl);
        $fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';

        $this->assign('fxurl',$fxurl);
    	$this->assign('key',$key);
    	$this->assign('type',$type);
    	$this->assign('newclass',$newclass);
    	$this->assign('rm',$rm);
    	$this->assign('rmnum',count($rm));
    	
    	$this->assign('purl',$purl);
    	$this->display();
    }
    function ajaxfy(){

    	$key = check_input($_GET['key']);
    	$type = check_input($_GET['type']);
    	$p = intval($_GET['p']);
    	$long = 6;
    	$start = ($p+1)*$long;
    	$purl = '';
    	if(!empty($key)){
    		$where = " and Title like'%".$key."%'";
    		$purl .= '/key/'.$key;
    	}
    	if(!empty($type)){
    		$where = " and ClassID =$type";
    		$purl .= '/type/'.$type;
    	}
    	$rm =  M('tbfitmentguide')->where(" ClassID!=179 and Status=1 and Hot=1".$where)->limit($start,$long)->order('UpdateTime desc')->select();
    	$html = '';
        //var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($rm)){
	    	foreach ($rm as $k=>$vo){
	    		// <p class="y-1-con y-c">'.$vo[Intro].'</p>
	    	$html .= '
	                    <div class="yuedu-list-2">
	                    	<div class="y-2-r fl"><a href="/'.$this->city['DOMAIN'].'/WxUser/wz/id/'.$vo[ID].'"><img src="'.$vo['DefaultPicUrl'].'" /></a></div>
	                    	<div class="y2l">
                            <div class="y-2-l y2l-2">
	                            <p class="y-l-tit"><a href="/'.$this->city['DOMAIN'].'/WxUser/wz/id/'.$vo[ID].'">'.$vo['Title'].'</a></p>
	                           
	                            <p class="y-1-con2">浏览：'.$vo[Hits].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$vo[Author].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   '.$vo[UpdateTime].'</p>
	                        </div>
	                        </div>
	                        <div class="clear"></div>
	                    </div>
	    			';
	    	}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    }
    //个人案例
    public function allist(){
    	$openid = $this->topid;//我的openid
    	$this->assign('openid',$openid);
    	$key = I('get.key','');
    	$searchishas = 1;
    	$where = "(UID='".$_SESSION['wxpromoter']['Id']."' or Uid=2673) and status=1";
    	if(!empty($key)){
    		$where = " and NAME like'%".$key."%'";
    		$purl .= '/key/'.$key;
    		$_SESSION['searchkey'] = $key;
    	}
    	//分享链接
        //转发时的信息
        $zhuanfainfo = $_SESSION['wxpromoter'];
        $ispromoters = M('tbpromoter')->where(array('wxopenid'=>$_SESSION['wxinfo']['openid']))->find();
        if(!empty($ispromoters)){
            $zhuanfainfo = $ispromoters;
        }
    	$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/allist/topid/'.$zhuanfainfo['Id'];
    	$returnurl = urlencode($returnurl);
    	$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';

    	$this->assign('fxurl',$fxurl);
    	//案例
    	$allist = M('casedecorate')->where($where)->limit(6)->order('UPDATETIME desc')->select();
		if(empty($allist)){
			unset($where);
			$searchishas = 0;
			$allist = M('casedecorate')->where($where)->limit(6)->order('UPDATETIME desc')->select();
		}
    	$this->assign('searchishas',$searchishas);
    	$this->assign('key',$key);
    	$this->assign('purl',$purl);
    	$this->assign('allist',$allist);
    	$allistnum = M('casedecorate')->where($where)->order('UPDATETIME desc')->count();
    	$this->assign('allistnum',$allistnum);
    	$this->display();
    }
    function ajaxmoreal(){

    	$key = check_input($_GET['key']);
    	$p = intval($_GET['p']);
    	$long = 6;
    	$start = ($p+1)*$long;
    	$purl = '';
    	$where = "(UID='".$_SESSION['wxpromoter']['Id']."' or Uid=2673) and status=1";
    	if($key != ''){
    		$where = " and NAME like'%".$key."%'";
    		$purl .= '/key/'.$key;
    	}
    	$rm =  M('casedecorate')->where($where)->limit($start,$long)->order('UPDATETIME desc')->select();
    	$html = '';
    	//var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($rm)){
    		foreach ($rm as $k=>$vo){
    			//<p class="y-1-con y-c">'.$vo[ID].htmlspecialchars_decode($vo['DESCRIPTION']).'</p>
    		
    			$html .= '
	                    <div class="yuedu-list-2">
	                    	<div class="y-2-r fl"><a href="/'.$this->city['DOMAIN'].'/WxUser/al/id/'.$vo[ID].'">
	                    			<img src="'.$vo['IMAGE'].'" /></a></div>
	                    	<div class="y2l">
                            <div class="y-2-l y2l-2">
	                            <p class="y-l-tit"><a href="/'.$this->city['DOMAIN'].'/WxUser/al/id/'.$vo[ID].'">'.$vo['NAME'].'</a></p>
	                            <p class="y-1-con2">浏览：'.$vo[Hits].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$vo[Author].'  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  '.$vo[UPDATETIME].'</p>
	                        </div>
	                         </div>   		
	                        <div class="clear"></div>
	                    </div>
	    			';
    		}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    }
    //个人案例
    public function al(){
    	$openid = $this->topid;//我的openid
		$this->assign('openid',$openid);
    	$info = $this->wxuserinfo;
		$id = I('get.id',0);
		if(empty($id)){
			echo '参数错误';exit;
		}
    	$al = M('casedecorate')->where("ID='".$id."'")->find();
    	//案例
    	$alimg = M('case_image')->where("CID='".$al['ID']."'")->select();

    	//加入访问记录表
    	$vuser = M('weixinuserlog')->where("openid = '".$_SESSION['wxinfo']['openid']."'")->find();
    	$data['uid'] = $vuser['id'];
    	$data['nickname'] = $_SESSION['wxinfo']['nickname'];
    	$data['pid'] = $_SESSION['wxpromoter']['Id'];
    	$data['cid'] = $id;
    	$data['addtime'] = date('Y-m-d H:i:s');;
    	$data['title'] = $al['NAME'];
    	$data['cityname'] = $this->city['NAME'];
    	M('visitlogo')->add($data);
    	//var_dump(M()->getLastSql());
    	//分享链接
        //转发时的信息
        $zhuanfainfo = $_SESSION['wxpromoter'];
        $ispromoters = M('tbpromoter')->where(array('wxopenid'=>$_SESSION['wxinfo']['openid']))->find();
        if(!empty($ispromoters)){
            $zhuanfainfo = $ispromoters;
        }
    	$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/al/id/'.$id.'/topid/'.$zhuanfainfo['Id'];
    	//$returnurl = urlencode($returnurl);
    	$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
    
        //var_dump($fxurl);exit;
    	$this->assign('fxurl',$fxurl);
    	
    	$this->assign('info',$info);
    	$this->assign('al',$al);
    	$this->assign('alimg',$alimg);
    	//热门文章等 最新添加的分类里的文章
		$newclass = M('tbfitmentguideclass')->where(" ClassID!=179 and ParentID=0")->limit(3)->order('ClassID desc')->select();
    	$rm =  M('tbfitmentguide')->where(" InIndex=1 and ClassID!=179 and Status=1 and Hot=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz1 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[0]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz2 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[1]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz3 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[2]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
		
		//评论功能
		$comment = M('comment')->where('cid='.$id.' and state=1'  )->order('addtime desc')->limit(6)->select();
    	$commentlike = M('commentlike')->where('cid='.$id)->order('addtime desc')->limit(6)->select();
		$this->assign('commentlike',$commentlike);
		$this->assign('comment',$comment);
		$commentlikenum = M('commentlike')->where('cid='.$id)->order('addtime desc')->count();
		$commentnum = M('comment')->where('cid='.$id.' and state=1'  )->order('addtime desc')->count();
    	$this->assign('commentlikenum',$commentlikenum);
    	$this->assign('commentnum',$commentnum);
    	
    	//最新的评论
    	$commid = I('get.commid',0);
    	$commentnew = 0;
    	if(!empty($commid)){
    		$commentnew = M('comment')->where('id='.$commid)->find();
    		$this->assign('commentnew',$commentnew);
    	}
    	$this->assign('commid',$commid);
    	
		//点击加1
		M()->query('update casedecorate set Hits=Hits+1 where id='.$id);
    	$this->assign('newclass',$newclass);
    	$this->assign('rm',$rm);
    	$this->assign('wz1',$wz1);
    	$this->assign('wz2',$wz2);
    	$this->assign('wz3',$wz3);
		$this->assign('qudao','案例');
		$this->assign('utm_term',$al['NAME']);
		if(strpos($al['IMAGE'], 'http://')){
			$fximg = $al['IMAGE'];
		}else{
			$fximg = 'http://'.$_SERVER['SERVER_NAME'].$al['IMAGE'];
		}
		
		$this->assign('fximg',$fximg);
		$this->assign('title',$al['NAME']);
		$this->assign('description',$al['DESCRIPTION']);
    	$this->assign('type','c');
    	$this->display();
    }

    //个人案例
    public function wz(){
    	$openid = $this->topid;//我的openid
    	$this->assign('openid',$openid);
    	$info = $this->wxuserinfo;
    	$id = I('get.id',0);
    	if(empty($id)){
    		echo '参数错误';exit;
    	}
    	$wz = M('tbfitmentguide')->where("ID='".$id."'")->find();

    	//加入访问记录表
    	$vuser = M('weixinuserlog')->where("openid = '".$_SESSION['wxinfo']['openid']."'")->find();
    	$data['uid'] = $vuser['id'];
    	$data['nickname'] = $_SESSION['wxinfo']['nickname'];
    	$data['pid'] = $_SESSION['wxpromoter']['Id'];
    	$data['cid'] = $id;
    	$data['addtime'] = date('Y-m-d H:i:s');;
    	$data['title'] = $wz['Title'];
    	$data['cityname'] = $this->city['NAME'];
    	
    	M('visitlogo')->add($data);
    	
    	//分享链接
        //转发时的信息
        $zhuanfainfo = $_SESSION['wxpromoter'];
        $ispromoters = M('tbpromoter')->where(array('wxopenid'=>$_SESSION['wxinfo']['openid']))->find();
        if(!empty($ispromoters)){
            $zhuanfainfo = $ispromoters;
        }
    	$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/wz/id/'.$id.'/topid/'.$zhuanfainfo['Id'];
    	$returnurl = urlencode($returnurl);
    	$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
    
    	$this->assign('fxurl',$fxurl);
    	 
    	$this->assign('info',$info);
    	$this->assign('al',$wz);
    	//热门文章等 最新添加的分类里的文章
		$newclass = M('tbfitmentguideclass')->where(" ClassID!=179 and ParentID=0")->limit(3)->order('ClassID desc')->select();
    	$rm =  M('tbfitmentguide')->where(" InIndex=1 and ClassID!=179 and Status=1 and Hot=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz1 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[0]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz2 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[1]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();
    	$wz3 =  M('tbfitmentguide')->where(" InIndex=1 and ClassID=".$newclass[2]['ClassID']." and Status=1")->limit(4)->order('UpdateTime desc')->select();

    	//评论功能
    	$comment = M('comment')->where('wid='.$id.' and state=1')->order('addtime desc')->limit(6)->select();
    	$commentlike = M('commentlike')->where('wid='.$id)->order('addtime desc')->limit(6)->select();
		$this->assign('commentlike',$commentlike);
    	$this->assign('comment',$comment);
		$commentlikenum = M('commentlike')->where('wid='.$id)->order('addtime desc')->count();
		$commentnum = M('comment')->where('wid='.$id.' and state=1'  )->order('addtime desc')->count();
    	$this->assign('commentlikenum',$commentlikenum);
    	$this->assign('commentnum',$commentnum);
    	//最新的评论
    	$commentnew = 0;
    	$commid = I('get.commid',0);
    	if(!empty($commid)){
    		$commentnew = M('comment')->where('id='.$commid)->find();
    		$this->assign('commentnew',$commentnew);
    	}
    	$this->assign('commid',$commid);
    	//点击加1
    	M()->query('update tbfitmentguide set Hits=Hits+1 where id='.$id);
    	$this->assign('newclass',$newclass);
    	$this->assign('rm',$rm);
    	$this->assign('wz1',$wz1);
    	$this->assign('wz2',$wz2);
    	$this->assign('wz3',$wz3);
		$this->assign('qudao','文章');
		$this->assign('utm_term',$wz['Title']);
		if(strpos($wz['DefaultPicUrl'], 'http://')){
			$fximg = $wz['DefaultPicUrl'];
		}else{
			$fximg = 'http://'.$_SERVER['SERVER_NAME'].$wz['DefaultPicUrl'];
		}
		
		$this->assign('fximg',$fximg);
		$this->assign('title',$wz['Title']);
		$this->assign('description',$wz['Intro']);
    	$this->assign('type','w');
    	$this->display();
    }
    function zfcount(){//转发数目计算
    	$id = I('get.id',0);
    	$type = I('get.type',0);
    	if(!empty($id)&&!empty($type)){
    		if($type == "c"){
				M()->query('update casedecorate set zhuanfa=zhuanfa+1 where id='.$id);
    		}
    		if($type == "w"){
    			M()->query('update tbfitmentguide set zhuanfa=zhuanfa+1 where id='.$id);
    		}
    	}
    }
    function ajaxcomment(){

    	$id = intval($_GET['id']);
    	$p = intval($_GET['p']);
    	$type = $_GET['type'];
    	if(empty($id)){
    		echo '参数错误！';exit;
    	}
    	$long = 6;
    	$start = ($p+1)*$long;
    	$purl = '';
    	if($type == 'c'){
    		$type = 'c';
    	}else if($type == 'w'){
    		$type = 'w';
    	}else{
    		$type = 'c';
    	}
    	$rm =  M('comment')->where($type.'id='.$id)->limit($start,$long)->order('addtime desc')->select();
    	$html = '';
    	//var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($rm)){
    		foreach ($rm as $k=>$vo){
    			$html .= '
            <div class="jxpl-con-part2">
            	<div class="j-c-p2-l fl"><img src="'.$vo['headimgurl'].'" /></div>
                <div class="jcp2r">
                <div class="j-c-p2-r ">
                	<p class="j-c-p2-r-1">'.$vo['nickname'].'</p>
                    <p class="j-c-p2-r-2">'.$vo['content'].'</p>
                    <div class="j-c-p2-r-3">
                        <div class="j-c-p2-r-3-right fr">
                    		<a href="javascript:huifu('.$vo['id'].',"'.$vo['nickname'].'");">
                    				<img src="/Public//wx/images/dp-27.jpg" />
                    				<span>回复</span></a><a href="javascript:plzan('.$vo['id'].','.$vo['zan'].');">
                    						<img src="/Public//wx/images/dp-26.jpg" /><span id="plzannum'.$vo['id'].'">'.$vo['zan'].'</span></a></div>
                        <div class="clear"></div>
                   	</div>
                  </div>
                </div>
                <div class="clear"></div>
            </div>
	    			';
    		}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    }
    function ajaxcommentlike(){

    	$id = intval($_GET['id']);
    	$p = intval($_GET['p']);
    	$type = $_GET['type'];
    	if(empty($id)){
    		echo '参数错误！';exit;
    	}
    	$long = 6;
    	$start = ($p+1)*$long;
    	$purl = '';
    	if($type == 'c'){
    		$type = 'c';
    	}else if($type == 'w'){
    		$type = 'w';
    	}else{
    		$type = 'c';
    	}
    	$rm =  M('commentlike')->where($type.'id='.$id)->limit($start,$long)->order('addtime desc')->select();
    	$html = '';
    	//var_dump(M()->getLastSql());
    	$data['p'] = ($p+1);
    	if(!empty($rm)){
    		foreach ($rm as $k=>$vo){
    			$html .= '<span><img src="'.$vo['headimgurl'].'" /></span>';
    		}
    	}
    	$data['html'] = $html;
    	echo json_encode($data);
    }
    function plzan(){
    	$id = intval($_GET['id']);
    	M()->query('update comment set zan=zan+1 where id='.$id);
    	echo 1;
    }
    public function addcomment(){
    	$content = check_input($_POST['content']);
    	$content = urlencode($content);
    	$type = check_input($_POST['type']);
    	$pid = intval($_POST['pid']);
    	$cid = intval($_POST['cid']);
    	//var_dump($_POST);exit;
    	$wxuser = $_SESSION['wxinfo'];
    		$returnurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/addcommentin/content/".$content."/type/".$type."/pid/".$pid."/cid/".$cid;
    		//$returnurl = urlencode($returnurl);
    		//var_dump($returnurl);exit;
    	if(empty($wxuser['headimgurl']) && empty($_SESSION['wxinfo']['nickname'])){
    		$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';

    		//header("Content-type:text/html;charset=utf-8");
    		header("location:".$fxurl);
    	}else{
    		//header("Content-type:text/html;charset=utf-8");
    		header("location:".$returnurl);
    	}
    	
    }
    
    function addcommentin(){
    	if(empty($_SESSION['wxinfo']['headimgurl']) && empty($_SESSION['wxinfo']['nickname'])){
    		$wxusers = $this->gethurltmls();
			$wxuser = M('weixinuserlog')->where("openid ='".$wxusers['openid']."'")->find();
    		$this->updatewxuser($wxusers);
    		$_SESSION['wxinfo'] = $wxuser;
    	}
    	$wxuser = $_SESSION['wxinfo'];
    	$content = check_input($_GET['content']);
    	$content = iconv("gb2312//IGNORE","utf-8",$content);
    	$type = check_input($_GET['type']);

    	$pid = intval($_GET['pid']);
    	$wid = 0;
    	$cid = 0;
    	if($type == 'c'){
    		$cid = intval($_GET['cid']);
    		$ztname = M('casedecorate')->where("ID='".$cid."'")->getField('NAME');
    	}
    	if($type == 'w'){
    		$wid = intval($_GET['cid']);
    		$ztname = M('tbfitmentguide')->where("ID='".$wid."'")->getField('Title');
    	}
    	//var_dump($_GET);exit;
    	$return['msg'] = '回复失败';
    	if(!empty($content)){
    		
    		$user = M('weixinuserlog')->where("openid='".$wxuser['openid']."'")->find();
    		$nickname = $wxuser['nickname'];
    		if(!empty($pid)){
    			$puser = M('weixinuserlog')->where('id='.$pid)->find();
    			$content = str_replace('回复“'.$puser['nickname'].'”:', '', $content);
    			//$nickname = '回复“'.$puser['nickname'].'”:';
    			$data['pid'] = $pid;
    		}
    		$data['uid'] = $user['id'];
    		$data['content'] = $content;
    		$data['nickname'] = $nickname;
    		$data['ztname'] = $ztname;
    		$data['headimgurl'] = $wxuser['headimgurl'];
    		$data['cid'] = $cid;
    		$data['wid'] = $wid;
    		$data['addtime'] = date('Y-m-d H:i:s');
    		//var_dump($user);
    		$res = M('comment')->add($data);
    		//$return['msg'] = M()->getLastSql();
    		if($res){
    			if($type == 'c'){
    				$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/al/id/".$_GET['cid']."/commid/".$res;
    				header("location:".$fxurl);
    			}
    			if($type == 'w'){
    				$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/wz/id/".$_GET['cid']."/commid/".$res;
    				header("location:".$fxurl);
    			}
    		}
    	}
    }

    public function like(){//喜欢的微信跳转
    	

    	$liketype = check_input($_POST['liketype']);
    	$type = check_input($_POST['type']);
    	$cid = intval($_POST['cid']);
/*
    	*/
    	if($type == 'c'){
    		$ishas = M('commentlike')->where('uid='.$_SESSION['wxinfo']['id'].' and cid='.$cid)->find();
    		if($ishas){
    		$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/al/id/".$cid;
			echo '<script>alert("您已经参与过了！");window.location.href="'.$fxurl.'";</script>';
    		
			exit;
    		}
    	}
    	if($type == 'w'){
    		$ishas = M('commentlike')->where('uid='.$_SESSION['wxinfo']['id'].' and wid='.$cid)->find();
    		if($ishas){
    		$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/wz/id/".$cid;
			echo '<script>alert("您已经参与过了！");window.location.href="'.$fxurl.'";</script>';
    		
			exit;
    		}
    	}
    	
    	//var_dump($_POST);exit;
    	$wxuser = $_SESSION['wxinfo'];
    	if($liketype == 'down'){
    		$returnurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/altoudown/id/".$cid."/type/".$type;
    	}else{
    		$returnurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/altouup/id/".$cid."/type/".$type;
    		
    	}
    	//$returnurl = urlencode($returnurl);
    	//var_dump($returnurl);exit;
    	if(empty($_SESSION['wxinfo']['headimgurl']) && empty($_SESSION['wxinfo']['nickname'])){
    		$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    
    		header("location:".$fxurl);
    	}else{
    		header("location:".$returnurl);
    	}
    	 
    }
    function altouup(){
    	//var_dump($_SESSION['wxinfo']);exit;
    	$id = I('get.id',0);
    	$type = check_input($_GET['type']);
    	if(empty($_SESSION['wxinfo']['headimgurl']) && empty($_SESSION['wxinfo']['nickname'])){
    		$wxusers = $this->gethurltmls();
    		$this->updatewxuser($wxusers);
			$wxuser = M('weixinuserlog')->where("openid ='".$wxusers['openid']."'")->find();
    		$_SESSION['wxinfo'] = $wxuser;
    	}
    	$user = $_SESSION['wxinfo'];
    	$wid = 0;
    	$cid = 0;
    	if($type == 'c'){
    		$cid = $id;
	    	
    			$res = M()->query('update casedecorate set isup=isup+1 where id='.$id);
    	}
    	if($type == 'w'){
    		$wid = $id;
    			$res = M()->query('update tbfitmentguide set isup=isup+1 where id='.$id);
    	}
    	$data['addtime'] = date('Y-m-d H:i:s');
    	$data['wid'] = $wid;
    	$data['cid'] = $cid;
    	$data['uid'] = $user['id'];
    	$data['headimgurl'] = $user['headimgurl'];
    	$data['state'] = 1;
    	$res = M('commentlike')->add($data);
    		if($res){
    			if($type == 'c'){
    				$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/al/id/".$id;
    				header("location:".$fxurl);
    			}
    			if($type == 'w'){
    				$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/wz/id/".$id;
    				header("location:".$fxurl);
    			}
    		}
    }
    function altoudown(){
    	$id = I('get.id',0);
    	$type = check_input($_GET['type']);
    	if(empty($_SESSION['wxinfo']['headimgurl']) && empty($_SESSION['wxinfo']['nickname'])){
    		$wxusers = $this->gethurltmls();
			$wxuser = M('weixinuserlog')->where("openid ='".$wxusers['openid']."'")->find();
    		$this->updatewxuser($wxusers);
			//var_dump($wxusers);exit;
    		$_SESSION['wxinfo'] = $wxuser;
    	}
    	$user = $_SESSION['wxinfo'];
    	$wid = 0;
    	$cid = 0;
    	if($type == 'c'){
    		$cid = $id;
    		$res = M()->query('update casedecorate set isdown=isdown+1 where id='.$id);
    	}
    	if($type == 'w'){
    		$wid = $id;
    		$res = M()->query('update tbfitmentguide set isdown=isdown+1 where id='.$id);
    	}
    	$data['addtime'] = date('Y-m-d H:i:s');
    	$data['wid'] = $wid;
    	$data['cid'] = $cid;
    	$data['uid'] = $user['id'];
    	$data['headimgurl'] = $user['headimgurl'];
    	$data['state'] = 2;
    	$res = M('commentlike')->add($data);
    		if($res){
    			if($type == 'c'){
    				$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/al/id/".$id;
    				header("location:".$fxurl);
    			}
    			if($type == 'w'){
    				$fxurl = "http://".$_SERVER['SERVER_NAME']."/".$this->cityname."/WxUser/wz/id/".$id;
    				header("location:".$fxurl);
    			}
    		}
    }
    //报名
    public function bm(){
    	$topid = $_SESSION['wxpromoter']['Id'];
    	$name = I('post.name',0);
    	$phone = I('post.phone',0);
    	$xq = I('post.xq',0);
    	$datas['state'] = 0;
    	$datas['msg'] = '参数错误';
    	if(empty($name)){
			$datas['msg'] = '请输入姓名！';
			echo json_encode($datas);
			exit;
    	}
    	if(empty($phone)){
			$datas['msg'] = '请输入电话号码！';
			echo json_encode($datas);
			exit;
    	}
    	$hdid = I('post.hdid',0);
    	if(empty($hdid)){
	    	$isbm = M('reservation_construction')->where("PHONE = '".$phone."'")->find();
	    	if($isbm){
				$datas['msg'] = '这个号码已经报过名了！';
				echo json_encode($datas);
				exit;
	    	}
    	}else{

    		$isbm = M('reservation_construction')->where("PHONE = '".$phone."' and hdid=".$hdid)->find();
    		if($isbm){
    			$datas['msg'] = '这个号码已经报过名了！';
    			echo json_encode($datas);
    			exit;
    		}
    		$data['hdid'] = $hdid;
    	}
		$wxuserinfo = $_SESSION['wxpromoter'];
        $tphone = $wxuserinfo['PromoterTel'];
        $tname = $wxuserinfo['PromoterName'];
		//如果已经通过别的推广员关注则按原来的推广员表面；
		$openid = $_SESSION['wxinfo']['openid'];
     	//$isygz = M('tbpromonterwx')->where(array('useropenid'=>$openid))->find();//如果已经关注    
     	//if(!empty($isygz) && !empty($isygz['promonterid'])){
     		//$topid = $isygz['promonterid'];
           // $tgy = M('tbpromoter')->where(array('Id'=>$topid))->find();
            //var_dump($tgy);
            //$tphone = $tgy['PromoterTel'];
           // $tname = $tgy['PromoterName'];
     //	}
        //var_dump($tphone);exit;
     	$time = date('Y-m-d H:i:s');
     	//如果是推广员填的用户
        //$isbd = M('tbpromonterwx')->where("useropenid='".$_SESSION['wxinfo']['openid']."'")->find();
        //
        //if($isbd){
            //$data['wx'] = $_SESSION['wxinfo']['openid'];
        //}
     	//$istgy = M('tbpromoter')->where("wxopenid='".$openid."'")->find();
     	//if(empty($istgy)){
	    	$data['uid'] = $_SESSION['wxinfo']['id'];
	    	$data['wx'] = $_SESSION['wxinfo']['openid'];
            $data['wxdatetime'] = $time;
            //$tphone = 0;
     	///}else{
          //  $tphone = 0;
        //}
    	$data['PromoterId'] = $topid;
    	$data['NAME'] = $name;
    	$data['TIME'] = $time;
    	$data['CID'] = $this->city['ID'];
    	$data['PHONE'] = $phone;
    	$data['community'] = $xq;
    	$data['addtime'] = $time;
    	$data['PromoterName'] = $wxuserinfo['PromoterName'];
    	$data['qudao'] = I('post.qudao',0);
    	$data['utm_term'] = I('post.utm_term',0);
    	$res = M('reservation_construction')->add($data);
    	if($res){
        //var_dump($tphone);
            if(!empty($tphone)){
                $msg = '推广员'.$tname.'您好，您有一个新的订单 姓名：'.$name.' 电话：'.$phone.' 小区： '.$xq.' 报名时间：'.date('Y-m-dH:i:s').' 请及时处理';
                $msg = urlencode($msg);
                $res = $this->sendcode($tphone, $msg);
            }
    		$datas['state'] = 1;
			$datas['msg'] = '预约成功！';
			echo json_encode($datas);
			exit;
    	}
    }
    //扫码绑定用户
    function binduser(){
    	$openid = $_SESSION['wxinfo']['openid'];
    	$id = I('get.id',0);
    	$pid = I('get.pid',0);
    	//var_dump($openid);
    	//var_dump($id);
    	//var_dump($pid);exit;
    	if(!empty($id)&& !empty($openid)&& !empty($pid)){
    		$isbd = M('tbpromonterwx')->where("useropenid='".$openid."'")->find();
    		if(!empty($isbd['promonterid'])){
    			echo '<script>alert("此微信号已经绑定过经纪人了！");window.history.go(-1);</script>';exit;
    		}
    		$data['promonterid'] = $pid;
    		if(empty($isbd)){
    			$data['useropenid'] = $openid;
    			$data['addtime'] = date('Y-m-d H:i:s');;
    			M('tbpromonterwx')->add($data);
    		}else{
    			M('tbpromonterwx')->where("useropenid='".$openid."'")->save($data);
    		}
    		
    		$isuser = M('weixinuserlog')->where("openid='".$openid."'")->find();
    		$uid = $isuser['id'];
    		if(empty($isuser)){
    			$datas['openid'] = $openid;
    			$uid = M('weixinuserlog')->add($datas);
    		}
    		$dataa['uid'] = $uid;
    		$dataa['wx'] = $openid;
    		$dataa['wxdatetime'] = date('Y-m-d H:i:s');;
    		
    		M('reservation_construction')->where("ID='".$id."'")->save($dataa);
    		$this->display('bdsuccess');
    	}

    	echo '参数错误';exit;
    }
    function sess_refresh(){
    	$_SESSION['wxpromoter'] = $_SESSION['wxpromoter'];
    	$_SESSION['wxinfo'] = $_SESSION['wxinfo'];
    	$_SESSION['mytopid'] = $_SESSION['mytopid'];
    }
    //加载登录页面
	public function tjjjr(){
		$fromopenid = $_SESSION['wxpromoter']['Id'];
		//分享链接
		$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/register/topid/'.$fromopenid;
		$returnurl = urlencode($returnurl);
		$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';

		$this->assign('fxurl',$fxurl);
		
		$this->display();
		
	}
    
	public function register(){
        $topid = I('get.topid',0);//推广员的id
        $ispromoters = M('tbpromoter')->where(array('Id'=>$topid))->find();
        if(empty($ispromoters)){
            echo '<script>alert("上级推广员不存在！");window.history.go(-1);</script>';exit;
            exit;
        }else{
            $_SESSION['wxpromoter'] = $ispromoters;
        }
		$fromopenid = $ispromoters['Id'];
        $wxinfo = $this->gethurltmls();
        //var_dump($wxinfo);exit;
        $openid = $wxinfo['openid'];//我的openid
            //游客信息加入访问表中
            $ishas = M('weixinuserlog')->where("openid ='".$wxinfo['openid']."'")->find();
            //var_dump(M()->getLastSql());
            if(empty($ishas)){
                $data['openid'] = $wxinfo['openid'];
                $data['Promoterid'] = $topid;
                $data['nickname'] = $wxinfo['nickname'];
                $data['sex'] = $wxinfo['sex'];
                $data['province'] = $wxinfo['province'];
                $data['city'] = $wxinfo['city'];
                $data['country'] = $wxinfo['country'];
                $data['headimgurl'] = $wxinfo['headimgurl'];
                $data['intime'] = date('Y-m-d H:i:s');
                $res = M('weixinuserlog')->add($data);
                //var_dump(M()->getLastSql());
                $data['id'] = $res;
                $_SESSION['wxinfo'] = $data;
            }else if(empty($ishas['nickname'])){
                $this->updatewxuser($wxinfo);
                $ishas = M('weixinuserlog')->where("openid ='".$wxinfo['openid']."'")->find();
                $_SESSION['wxinfo'] = $ishas;
            }else{
                $_SESSION['wxinfo'] = $ishas;
            }
		
		//分享链接
		$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/register/topid/'.$topid;
		$returnurl = urlencode($returnurl);
		$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';

		$this->assign('fxurl',$fxurl);
		$this->display();
	}
	public function registerin(){

		$wxinfo = $_SESSION['wxinfo'];
		$pass = rand(111111,999999);
		$userpass = md5($pass);//'e10adc3949ba59abbe56e057f20f883e';
		$fromopenid =  $_SESSION['wxpromoter']['Id'];
		$openid = $wxinfo['openid'];
    	$code = I('post.mcode',0);
    	if($code != $_SESSION['mcode']){
			echo '<script>alert("验证码不正确！");window.history.go(-1);</script>';exit;exit;
    	}
		if(!empty($openid) && !empty($fromopenid)){
			$parent = M('tbpromoter')->where("Id='".$fromopenid."'")->find();
			//var_dump($parent);exit;
			if(!$parent){
				echo '<script>alert("来源推广员不存在！");window.history.go(-1);</script>';exit;
			}
			if($parent['level'] == 3){
				echo '<script>alert("您是三级推广员无法再添加下级了！");window.history.go(-1);</script>';exit;
			}
			//判断是否存在
			$ishas = M('tbpromoter')->where("wxopenid='".$openid."'")->find();
			if($ishas){
				echo '<script>alert("此微信已经注册！");window.history.go(-1);</script>';exit;
			}
			$phone = I('post.phone',0);
			if(empty($phone)){
				echo '<script>alert("请输入电话号码！");window.history.go(-1);</script>';exit;
			}
			$ishasphone = M('tbpromoter')->where("PromoterTel='".$phone."'")->find();
			if($ishasphone){
				echo '<script>alert("此电话号码已经注册！");window.history.go(-1);</script>';exit;
			}
			//var_dump($fromopenid);exit;
			$wxinfo = M('weixinuserlog')->where("openid='".$openid."'")->find();

			$data['pid'] = $parent['Id'];
			$data['level'] = ($parent['level']+1);
			$data['wxopenid'] = $openid;
			$data['userpass'] = $userpass;
			$data['PromoterTel'] = $phone;
            $data['CityID'] = $this->city['ID'];
            $data['CityName'] = $this->city['NAME'];
				$data['PromoterName'] = $wxinfo['nickname'];
				$data['nickname'] = $wxinfo['nickname'];
				$data['sex'] = $wxinfo['sex'];
				$data['province'] = $wxinfo['province'];
				$data['city'] = $wxinfo['city'];
				$data['country'] = $wxinfo['country'];
				$data['photo'] = $wxinfo['headimgurl'];
			$data['addtime'] = date('Y-m-d H:i:s');;
			$res = M('tbpromoter')->add($data);
            $data['Id'] = $res;
            $_SESSION['wxpromoter'] = $data;
			//var_dump( M('tbpromoter')->getLastSql());exit;
			if($res){

				$Content = '尊敬的顾客，恭喜您成为实创装饰经纪人，您的后台用户名为您所填写手机号，登陆密码是'.$pass.'，地址是：http://room.sitrue.cn/admin/,您可以在这里修改信息！。';
				$res = $this->sendcode($phone, $Content);
				echo '<script>alert("恭喜您！成功加入！");window.location.href="/'.$this->cityname.'/WxUser/regsucess";</script>';exit;
			}else{
				echo '<script>alert("加入失败！");window.history.go(-1);</script>';exit;
			}
		}else{
			
			echo '<script>alert("获取微信信息失败！");window.history.go(-1);</script>';
		}
		
	}
	public function regsucess(){

		$this->display();
		
	}
	function jump(){
		$openid = $this->getopenid();
		//var_dump($openid);exit;
		//是否是推广员推广员直接进自己的店铺
		$pro = M('tbpromoter')->where("wxopenid='".$openid."'")->find();
		//var_dump($pro);
		if(!empty($pro) && !empty($pro['wxopenid'])){
			$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/index/topid/'.$pro['Id'];
			$returnurl = urlencode($returnurl);
			$homeurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';

			
			header('location:'.$homeurl);
			exit;
		}
		$pro = M('tbpromonterwx')->where("useropenid='".$openid."'")->find();
		//var_dump($pro);exit;
		$pid = $pro['promonterid'];
		$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->cityname.'/WxUser/index/topid/'.$pid;
		$returnurl = urlencode($returnurl);
		$homeurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
			
		header('location:'.$homeurl);
	}
	public  function sjszan(){
		$id = I('get.id',0);
		if(!empty($id)){
			$iszan = M('tbpromonterzan')->where('uid='.$_SESSION['wxinfo']['id'])->find();
			if(!empty($iszan)){
				echo '2';exit;
			}
			$res = M()->query('update tbpromoter set zan=zan+1 where Id='.$id);
			$data['pid'] = $id;
			$data['uid'] = $_SESSION['wxinfo']['id'];
			$data['openid'] = $_SESSION['wxinfo']['openid'];
			$data['addtime'] = date('Y-m-d H:i:s');;
			M('tbpromonterzan')->add($data);
			echo '1';
		}
	}
	//注册验证码
    public function mcode($phone=''){
    	if(empty($phone))
    	$phone = I('post.phone',0);
    	$Verify = new \Think\Verify();
    	if(empty($phone))
    		$this->error('请填写手机号');
    		
    	$code = rand(1000,9999);
			$_SESSION['mcode'] = $code;
    	//var_dump($_SESSION['mcode']);
			$_SESSION['mtime'] = time();
    		$Content = '尊敬的顾客，您好！为了确保您信息的真实性，您本次操作的验证码是'.$code.'，请在页面填写验证码完成验证。';
			$res = $this->sendcode($phone, $Content);
			if($res){
    			$this->success();
			}
    }
    protected function sendcode($phone,$Content){

    	//$Content = '尊敬的‘'.$username.'’先生您好，'.$info[0]['phonecontent'].'，详情：'.$phone;
    	$HttpUrl = "http://125.208.3.91:8888/sms.aspx?action=send&userid=7080&account=SC1&password=sc51567769";
    	$HttpUrl .=  "&Mobile=".$phone;
    	$HttpUrl .=   "&Content=".$Content;
    	$HttpUrl .=   "&sendTime=&extno=";
        //var_dump($HttpUrl);exit;
       // $HttpUrl = 'http://125.208.3.91:8888/sms.aspx?action=send&userid=7080&account=SC1&password=sc51567769&Mobile=15110012192&Content=推广员测试您好，您有一个新的订单 姓名：测试6 电话：11231241245 小区： 111 报名时间：2015-08-1910:35:31 请及时处理&sendTime=&extno=';

    	$res = file_get_contents($HttpUrl);
        //var_dump($res);exit;
    	$xml = simplexml_load_string($res);
       // var_dump($res);exit;
    	if($xml->message == 'ok'){
    		return true;
    		exit;
    	}else{

    		return false;
    		exit;
    	}
    }
}