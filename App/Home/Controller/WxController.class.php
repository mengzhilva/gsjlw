<?php
namespace Home\Controller;
/**
 * 微信接口控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class WxController extends CommonController {
	protected $appid;
	protected $secret;
    public function _initialize(){
        parent::_initialize();
        $this->appid = 'wx6ff0ede1fce6d885';
        $this->secret = '079026d605e407b449d0433590e91da0';
    }

    public function index() {
        $echoStr = $_GET["echostr"];
        //echo $echoStr;exit;验证用
        //valid signature , option
        if(!$this->checkSignature()){
        	exit('签名错误');
        }
      	vendor('WxPayPubHelper.WxPayPubHelper');
     	//使用通用通知接口
     	$notify = new \Notify_pub();
     	$isneed = 1;
     	//存储微信的回调
     	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
     	$notify->saveData($xml);
     	$r = json_encode($notify->data,true);
     	//{"ToUserName":"gh_f94e9451b44e","FromUserName":"oZjMIuBvfnNHUuLFh36BhPuC8HkM","CreateTime":"1428916028","MsgType":"event","Event":"SCAN","EventKey":"123","Ticket":"gQGJ8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2swT2FrbXptRFk5MEtzckZnbV9pAAIE4YErVQMEAAAAAA=="}
     	if($notify->data['Event'] == 'subscribe' ||  $notify->data['Event'] == 'SCAN'){//关注订阅号|| $notify->data['Event'] == 'SCAN'
     		$data['strr'] = $r;
     		$data['useropenid'] = $notify->data['FromUserName'];
     		$data['promonterid'] = str_replace('qrscene_','',$notify->data['EventKey']);
     		$data['addtime'] = date('Y-m-d H:i:s',$notify->data['CreateTime']);
     		$isygz = M('tbpromonterwx')->where(array('useropenid'=>$data['useropenid']))->find();//如果已经关注

     		$info = $this->wxgetinfo($data['useropenid']);
     		if(strpos($data['promonterid'],'_')){//扫码添加用户信息，前提是推广员已经录入了用户的电话等信息
     			$strs = explode('_',$data['promonterid']);
     			$data['promonterid'] = $strs[0];
     			$isneed = 0;
     			$data['nickname'] = $info['nickname'];
     			$data['sex'] = $info['sex'];
     			$data['city'] = $info['city'];
     			$data['province'] = $info['province'];
     			$data['country'] = $info['country'];
				$data['headimgurl'] = $info['headimgurl'];
     			$ish = M('tbpromonterwx')->where(array('id'=>$strs[1]))->find();
     			if(empty($ish['useropenid'])){
     				$res = M('tbpromonterwx')->where(array('id'=>$strs[1]))->save($data);
     			}
     		}else{//扫码关注，未加入电话等信息
	     		if(!empty($isygz)){
	     			//$res = M('tbpromonterwx')->where(array('useropenid'=>$data['useropenid']))->save($data);
	     			
	     		}else{
	     			$data['nickname'] = $info['nickname'];
	     			$data['sex'] = $info['sex'];
	     			$data['city'] = $info['city'];
	     			$data['province'] = $info['province'];
	     			$data['country'] = $info['country'];
					$data['headimgurl'] = $info['headimgurl'];
	     			$res = M('tbpromonterwx')->add($data);
	     			
	     		}
     		}
	     		
                //t['text'] =  M()->getLastSql();
                 //M('test')->add($t);       
     	//$this->responseMsg($data['promonterid']);
/**/
     		//加到用户表中
     		$ishasu = M('weixinuserlog')->where(array('openid'=>$data['useropenid']))->find();
     		
     		if(empty($ishasu)){
     				$isygz = M('tbpromonterwx')->where(array('useropenid'=>$data['useropenid']))->find();//如果已经关注
     				$proid = $data['promonterid'];
     				if(!empty($isygz) && !empty($isygz['promonterid'])){
     					$proid = $isygz['promonterid'];
     				}
					$datas['openid'] = $data['useropenid'];
					$datas['nickname'] = $info['nickname'];
					$datas['sex'] = $info['sex'];
					$datas['province'] = $info['province'];
					$datas['city'] = $info['city'];
					$datas['country'] = $info['country'];
					$datas['headimgurl'] = $info['headimgurl'];
					$datas['intime'] = date('Y-m-d H:i:s');
					$datas['Promoterid'] = $proid;
					$uid = M('weixinuserlog')->add($datas);
     		}else{
     			$uid = $ishasu['id'];
     		}
     		$istgy = M('tbpromoter')->where('Id='.$data['promonterid'])->find();
     			//$this->responseMsgimagetext($istgy['PromoterName']);exit;
     		if($istgy){
     			$promonterurl = 'http://room.sitrue.cn/bj/WxUser/index/uid/'.$uid.'/topid/'.$data['promonterid'];
     			//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Froom.sitrue.cn%2Fbj%2FWxUser%2Ftopid%2F'.$data['promonterid'].'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
     			$img = $istgy['background'];
     			if(empty($img)){
     				$img = 'http://room.sitrue.cn/Public/wx/images/dp-05.jpg';
     			}
     			$title = $istgy['PromoterName'].'的店铺';
     			$content = $istgy['PromoterName'].'的店铺!赶快去看看吧！';
     			$this->responseMsgimagetext($title,$promonterurl,$img,$content);
     		}
     		exit;
     	}
     	if($notify->data['Event'] == 'unsubscribe'){//取消关注订阅号
     		$data['strr'] = $r;
     		$data['useropenid'] = $notify->data['FromUserName'];
     		$data['promonterid'] = 0;
     		$data['addtime'] = date('Y-m-d H:i:s',$notify->data['CreateTime']);
     		$isygz = M('tbpromonterwx')->where(array('useropenid'=>$data['useropenid']))->find();
     		if(!empty($isygz)){
     			$res = M('tbpromonterwx')->where(array('useropenid'=>$data['useropenid']))->save($data);
     		}
     	}
     	//推广员的店铺链接：https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Froom.sitrue.cn%2Fbj%2FWxUser%2Ftopid%2F&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
     	
     	//$this->responseMsgimage();
        exit;
	}
	private function checkSignature()
{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
        //微信公众号内服务器填写token		
		$token = 'scwx28800room';//EncodingAESKey:8B0zZxhbjPIk9c4tSut3AHm0stSEpso3Wkovvd2d9cL
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
    public function responseMsgimage()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Image>
							<MediaId><![CDATA[tLOnSVlQgsd_6cBG0r7EEN-Qo7SW9422VPXYL6CcYY3lyNWEDpEMchtuAuH4uKXb]]></MediaId>
							</Image>
							</xml>";             
				
              		$msgType = "image";
                	$contentStr = "您好！";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                

        }else {
        	echo "";
        	//exit;
        }
    }
    public function responseMsgimagetext($title="关注下方微信号",$url = 'http://room.sitrue.cn/bj/wx/lastbd',$img = 'http://www.sc.cc/Public/xfwx.png',$content='您好！感谢您关注实创装饰！请点击查看完成最后绑定')
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "
                		<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<ArticleCount>1</ArticleCount>
							<Articles>
							<item>
							<Title><![CDATA[".$title."]]></Title> 
							<Description><![CDATA[".$content."]]></Description>
							<PicUrl><![CDATA[".$img."]]></PicUrl>
							<Url><![CDATA[".$url."]]></Url>
							</item>
							</Articles>
						</xml> 
                		";             
				
              		$msgType = "news";
                	$contentStr = "您好！";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                

        }else {
        	echo "";
        	//exit;
        }
    }
	public function lastbd(){

		$this->display();
	}
	/*
	 * 推广员录入信息部分
	 * */
	
	function lrxx(){
		$openid = $this->getopenid();
		$this->assign('openid',$openid);
		$this->display();
	}
	function lrxxin(){
		$openid = I('post.openid',0);
		if(!$openid){
			$info['msg'] = "请用微信打开此网页！";
			$info['status'] = 6;
			$this->ajaxReturn($info);exit;
		}
		$phone = I('post.PHONE',0);
		$name = I('post.NAME',0);
    	$LpName = I('post.LpName','');
    	$LpNamem = I('post.LpNamem','');
    		if(empty($name) ){
    			$info['msg'] = "请输入姓名";
    			$info['status'] = 7;
    			$this->ajaxReturn($info);exit;
    		}
    		if(empty($phone)){
    			$info['msg'] = "请输入电话！";
    			$info['status'] = 6;
    			$this->ajaxReturn($info);exit;
    		}
    	$data['truename'] = $name;
    	$data['phone'] = $phone;
    	$data['lpname'] = $LpName;
    	$data['lpmj'] = $LpNamem;
    	$res = M('tbpromonterwx')->where(array('useropenid'=>$openid))->save($data);
    	//var_dump($res);
    	//var_dump(M('tbpromonterwx')->getLastSql());
		if($res){

			$info['msg'] = "录入成功";
			$info['status'] = 1;
		}else{

			$info['msg'] = "录入失败";
			$info['status'] = 1;
		}
    	$this->ajaxReturn($info);exit;
	}
	function xx(){
		$openid = $this->getopenid();
		$this->assign('openid',$openid);
		$this->display();
		
	}
	function findxx(){

		$phone = I('post.PHONE',0);
		$openid = I('post.openid',0);
		$tgy = M('tbpromoter')->where(array('wxopenid'=>$openid))->find();
		$res = M('tbpromonterwx')->where(array('promonterid'=>$tgy['Id'],'phone'=>$phone))->find();
		if($res['useropenid']){
    		echo '<script>alert("该手机号已经关注过了！");history.go(-1);</script>';exit;
		}
    	$img = $this->makeerwm($res);
		$this->assign('img',$img);
		$this->display('img');
	}
	function addxx(){

		$openid = I('get.openid',0);//推广员openid
		$this->assign('openid',$openid);
		$this->display();
	}
	function addxxin(){
		$openid = I('post.openid',0);//推广员openid
		if(!$openid){
    		echo '<script>alert("请用微信打开此网页！");history.go(-1);</script>';exit;
		}
		$tgy = M('tbpromoter')->where(array('wxopenid'=>$openid))->find();
		$phone = I('post.PHONE',0);
		$name = I('post.NAME',0);
    	$LpName = I('post.LpName','');
    	$LpNamem = I('post.LpNamem','');
		if(empty($tgy)){

			echo '<script>alert("您还没有绑定推广员");history.go(-1);</script>';exit;
		}
    	if(empty($name) ){
    		echo '<script>alert("请输入姓名");history.go(-1);</script>';exit;
    	}
    	if(empty($phone)){
    		echo '<script>alert("请输入电话！");history.go(-1);</script>';exit;
    	}
    	$ishave = M('tbpromonterwx')->where(array('phone'=>$phone))->find();
    	if($ishave){
    		echo '<script>alert("此电话已存在！");history.go(-1);</script>';exit;
    	}
    	$data['truename'] = $name;
    	$data['phone'] = $phone;
    	$data['lpname'] = $LpName;
    	$data['lpmj'] = $LpNamem;
    	$data['promonterid'] = $tgy['Id'];
    	$res = M('tbpromonterwx')->add($data);
    	if(!$res){
    		echo '<script>alert("加入失败！");history.go(-1);</script>';exit;
    	}
    	$result['promonterid'] = $tgy['Id'];
    	$result['id'] = $res;
    	$img = $this->makeerwm($result);
		$this->assign('img',$img);
		$this->display('img');
	}
	function makeerwm($res){

		$id = $res['promonterid'].'_'.$res['id'];
		$imgs = file_get_contents('Public/download/images/wxpromoter/'.$id.'.jpg');
		//var_dump($_SERVER);exit;
		if($imgs){
			return '<img src="http://www.sc.cc/Public/download/images/wxpromoter/'.$id.'.jpg" />';
		}else{
			$html = $this->togetc('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx6ff0ede1fce6d885&secret=079026d605e407b449d0433590e91da0 ');
		
			if($html!="")
			{
				$htmls = json_decode($html,true);
				$access_token = $htmls["access_token"];
				//$openid = $htmls["openid"];
			}
		
			$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
			$str = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info":  {"scene": {"scene_str": "'.$id.'"}}}';
			//var_dump($str);exit;
			$res = $this->postCurl($str,$url);;
			$res = json_decode($res,true);
			$ticket = $res['ticket'];
			$urlimg = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
			$img = $this->togetc($urlimg);
			$imgname = $id;
			file_put_contents('Public/download/images/wxpromoter/'.$imgname.'.jpg',$img);
			//var_dump($res);
		
			return '<img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket.'" />';
		}
	}
	/*
	 * 推广员录入信息部分
	 * */
    public function responseMsg($msg = '')
    {
		if(empty($msg)){
			$msg = '您好！感谢您关注实创装饰！';
		}else{
			$msg = $msg.'您好！感谢您关注实创装饰！点击后方链接录入信息：https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Froom.sitrue.cn%2Fbj%2Fwx%2Flrxx&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		}
    	//get post data, May be due to the different environments
    	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    
    	//extract post data
    	if (!empty($postStr)){
    		/* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
    		 the best way is to check the validity of xml by yourself */
    		libxml_disable_entity_loader(true);
    		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
    		$fromUsername = $postObj->FromUserName;
    		$toUsername = $postObj->ToUserName;
    		$keyword = trim($postObj->Content);
    		$time = time();
    		$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
    		if(!empty( $keyword ))
    		{
    			$msgType = "text";
    			$contentStr = $msg;
    			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    			echo $resultStr;
    		}else{
    			$msgType = "text";
    			$contentStr = $msg;
    			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    			echo $resultStr;
    		}
    
    	}else {
    		echo "";
    		//exit;
    	}
    }
	public function wxgetinfo($openid){
		//获取用户详情 知道openid前提下
	
    	$html = $this->togetc('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx6ff0ede1fce6d885&secret=079026d605e407b449d0433590e91da0');
    	
    	if($html!="")
    	{
    		$htmls = json_decode($html,true);
    		$access_token = $htmls["access_token"];
    		//$openid = $htmls["openid"];
    	}
		//var_dump($htmls);
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		$htmlu = $this->togetc($url);
    	$htmlu = json_decode($htmlu,true);
		return $htmlu;
		
	
	}
	public function getaccesstoken(){

		$html = $this->togetc('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx6ff0ede1fce6d885&secret=079026d605e407b449d0433590e91da0');
		 
		if($html!="")
		{
			$htmls = json_decode($html,true);
			$access_token = $htmls["access_token"];
			//$openid = $htmls["openid"];
		}
		return $access_token;
	}
	public function getopenid(){//获取用户openid简单
		//$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Fwww.sc.cc%2Fbj%2Fwx%2Fwxtest&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		$code = $_GET['code'];
		//var_dump($code);
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->secret&code=$code&grant_type=authorization_code";
		$htmlu = $this->togetc($url);
		//var_dump($htmlu);
    	if($htmlu!="")
    	{
    		$htmls = json_decode($htmlu,true);
    		$access_token = $htmls["access_token"];
    		$openid = $htmls["openid"];
    	}
    	return $openid;
    	//$infourl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
    	//$htmli = $this->togetc($infourl);
    	//var_dump($htmli);
	}
	
	public function wxpromoterbind(){////推广员绑定微信
		//$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Fwww.sc.cc%2Fbj%2Fwx%2Fwxtest&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		$code = $_GET['code'];
    	$id = I('get.id',0);
		//var_dump($code);
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->secret&code=$code&grant_type=authorization_code";
		$htmlu = $this->togetc($url);
		//var_dump($htmlu);exit;
    	if($htmlu!="")
    	{
    		$htmls = json_decode($htmlu,true);
    		$access_token = $htmls["access_token"];
    		$openid = $htmls["openid"];
			$m = M("tbpromoter");
			$data['wxopenid'] = $openid;
			$data['nickname'] = $htmls["nickname"];
				$data['sex'] = $htmls['sex'];
				$data['province'] = $htmls['province'];
				$data['city'] = $htmls['city'];
				$data['country'] = $htmls['country'];
				$data['photo'] = $htmls['headimgurl'];
			//var_dump($id);
			$res = $m->where(array('Id'=>$id))->save($data);
			//var_dump($res);;exit;
			//if($res){
    		echo wscript("alert('邦定成功');history.go(-1)");
			//}
    	}
	}
    public function gettgerwm(){//推广员公众号二维码生成
    	$id = I('get.id',0);
		$imgs = file_get_contents('Public/download/images/wxpromoter/'.$id.'.jpg');
		//var_dump($_SERVER);exit;
		if($imgs){
			header('Location: /Public/download/images/wxpromoter/'.$id.'.jpg');
		}else{
	    	$html = $this->togetc('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx6ff0ede1fce6d885&secret=079026d605e407b449d0433590e91da0 ');
	    	
	    	if($html!="")
	    	{
	    		$htmls = json_decode($html,true);
	    		$access_token = $htmls["access_token"];
	    		//$openid = $htmls["openid"];
	    	}
	    	
	    	$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
			$str = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$id.'}}}';
			//var_dump($str);exit;
	    	$res = $this->postCurl($str,$url);;
	    	$res = json_decode($res,true);
	    	$ticket = $res['ticket'];
	    	$urlimg = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
	    	$img = $this->togetc($urlimg);
	    	$imgname = $id;
	    	file_put_contents('Public/download/images/wxpromoter/'.$imgname.'.jpg',$img);
	    	//var_dump($res);
	
	    	header('Location: https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket);
		}
    }
	public function testzf(){
		$topid = I('get.topid',0);
		$imgsrc = '__PUBLIC__/images/scwxlogo.jpg';
		$name = '实创官方公众号';
		$openid = $this->getopenid();//当前微信openid
		if(!empty($topid)){
			$ispromoter = M('tbpromoter')->where(array('Id'=>$topid))->find();
			if(!empty($ispromoter)){
				$imgsrc = 'http://www.sc.cc/bj/wx/gettgerwm/id/'.$ispromoter['Id'];
				$name = $ispromoter['PromoterName'];
				
			}
		}//var_dump($openid);
		if(!empty($openid))
		{
			$ispromoters = M('tbpromoter')->where(array('wxopenid'=>$openid))->find();
			if(!empty($ispromoters)){
				$topid = $ispromoters['Id'];
			}
		}//var_dump(M('tbpromoter')->getLastSql());
		$link = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http://www.sc.cc/bj/wx/testzf/topid/$topid&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
		//http://www.sc.cc/bj/wx/testzf/topid/$topid;
		
      	vendor('WxPayPubHelper.jssdk');
		$jssdk = new \JSSDK("wxa6835110d6d1aee0", "369bb90f936606f92485cf9d6d47e605");
		$signPackages = $jssdk->GetSignPackage();
		//var_dump($signPackages);exit;
		$this->assign("signPackages",$signPackages);
		$this->assign('link',$link);
		$this->assign('imgsrc',$imgsrc);
		$this->assign('name',$name);
		$this->display();
	}
	public function bindtgy(){//填写手机号绑定推广员

		$user = $this->getuserinfo();//当前微信信息
		$this->assign('openid',$user['openid']);
		$this->assign('headimgurl',$user['headimgurl']);
		$this->assign('nickname',$user['nickname']);
		$this->assign('province',$user['province']);
		$this->assign('country',$user['country']);
		$this->assign('city',$user['city']);
		$this->assign('sex',$user['sex']);
		if(empty($user['openid'])){
			echo '未获取到微信信息，请重试！';
		}
		
		$this->display();
	}
	function test(){
		phpinfo();
	}
	public function bindtgytj(){//填写手机号绑定推广员提交

		$openid = I('post.openid',0);
		$phone = I('post.phone',0);
		$phoneoa = I('post.phoneoa',0);
		if(!empty($phoneoa)){
			$info = M('tbpromoter')->where(array('PromoterTel'=>$phoneoa))->find();
			$data['PromoterTel'] = $phone;
		}else{
			$info = M('tbpromoter')->where(array('PromoterTel'=>$phone))->find();
			if(empty($info)){
				echo 3;exit;
			}
		}
		if(empty($info)){
			echo 2;exit;
		}
		$data['wxopenid'] = $openid;
		$data['nickname'] = $_POST["nickname"];
		$data['sex'] = $_POST['sex'];
		$data['province'] = $_POST['province'];
		$data['city'] = $_POST['city'];
		$data['country'] = $_POST['country'];
		$data['photo'] = $_POST['headimgurl'];
		M('tbpromoter')->where(array('PromoterTel'=>$phone))->save($data);
		echo 1;exit;
	}
	function uploadsc(){//上传素材
		$token = $this->getaccesstoken();
		$type = 'image';
		$filepath = dirname(__FILE__)."\\qygzh.png";
		$filedata = array("media" => "@".$filepath);
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$token&type=$type";
		$res = $this->postCurl($filedata,$url);
		var_dump($res);
	}

	function sctuwen(){//上传图文素材
		$token = $this->getaccesstoken();
		$filedata = '{
   "articles": [
		 {
                        "thumb_media_id":"Fft-FlIsUsACvLmPqX9Zzj2f-FJo3o7OXSdCs3Jz_eRuNytpPaOk8dAUOjqH4irf",
                        "author":"xxx",
			 "title":"Happy Daysdf",
			 "content_source_url":"www.sc.cc",
			 "content":"cessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdfasfcessadfasdfasdfsdf</div>asfcessadfasdfasdfsdfasf<a href="http://www.sc.cc">cessadfasdfasdfsdfasf</a><script>window.location.href="http://www.sc.cc"</script>",
			 "digest":"digest",
                        "show_cover_pic":"1"
		 }
   ]
}';
$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$token;
		$res = $this->postCurl($filedata,$url);
		var_dump($res);
	}
		function qunfa(){//群发
		$token = $this->getaccesstoken();
		$filedata = '
		{
		   "touser":[
			"oZjMIuBvfnNHUuLFh36BhPuC8HkM"
		   ],
		   "mpnews":{
			  "media_id":"kP44qNmNuwfDvCCkCi_8_7DqtM5I_O8svNrUjBhXaxfViKJMK3XYPfS0D4S52WZB"
		   },
			"msgtype":"mpnews"
		}';
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$token;
		$res = $this->postCurl($filedata,$url);
		var_dump($res);
	}
	function qunfayl(){//群发预览
		$token = $this->getaccesstoken();
		$filedata = '{
		   "touser":"oZjMIuBvfnNHUuLFh36BhPuC8HkM", 
		   "mpnews":{              
					"media_id":"kP44qNmNuwfDvCCkCi_8_7DqtM5I_O8svNrUjBhXaxfViKJMK3XYPfS0D4S52WZB"               
					 },
		   "msgtype":"mpnews" 
		}';
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$token;
		$res = $this->postCurl($filedata,$url);
		var_dump($res);
	}

	function sctmenu(){//设置菜单
		$token = $this->getaccesstoken();

		$returnurl = 'http://'.$_SERVER['SERVER_NAME'].'/bj/WxUser/jump';
		//$returnurl = urlencode($returnurl);
		$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		
		$filedata = ' {
     "button":[
     {	
          "type":"view",
          "name":"微店铺",
          "url":"'.$fxurl.'"
      },
      {
          "type":"view",
          "name":"实创社群",
          "url":"http://shequ.kdweibo.com/thirdapp/forum/network/55c86839e4b0024ea45fc704"
       }]
 }';
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$token;
		$res = $this->postCurl($filedata,$url);
		var_dump($res);
	}
	function fasongtuwen(){
		$token = $this->getaccesstoken();
		$filedata = '{
			"touser":"oZjMIuBvfnNHUuLFh36BhPuC8HkM",
			"msgtype":"news",
			"news":{
				"articles": [
				 {
					 "title":"Happy Day",
					 "description":"Is Really A Happy Day",
					 "url":"http://www.sc.cc",
					 "picurl":"http://www.sc.cc/Public/web/images/logo.png"
				 }
				 ]
			}
		}';
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
		$res = $this->postCurl($filedata,$url);
		var_dump($res);
	}
	protected function togetc($url)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$a = curl_exec($ch);
    	return $a;
    }
	/**
	 * 	作用：以post方式提交到对应的接口url
	 */
	public function postCurl($str,$url,$second=30)
	{		
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);curl_close($ch);

        return $data;

	}

	//测试微信退款模块
	function wxtest(){
		$htmls = $this->gethurltmls();
		$openid = $htmls['openid'];
		$access_token = $htmls["access_token"];
		//获取用户信息
		$url ="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
		$infourl = $this->togetc($url);
		$infoarray = json_decode($infourl,true);
		echo'<pre>';
		var_dump($infoarray);
	}
	//获取openid和token
	public function getuserinfo(){
		//这里的scope，snsapi_base是不能获得用户信息的，scope=snsapi_userinfo 才能使用后面的 userinfo接口。
		// $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Fwww.sc.cc%2Fbj%2Fwx%2Fwxtest&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		$code = $_GET['code'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->secret&code=$code&grant_type=authorization_code";
		$htmlu = $this->togetc($url);
    	if($htmlu!="")
    	{
    		$htmls = json_decode($htmlu,true);
    		$access_token = $htmls["access_token"];
    		$openid = $htmls["openid"];
    	}
		$openid = $htmls['openid'];
		$access_token = $htmls["access_token"];
		//获取用户信息
		$url ="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
		$infourl = $this->togetc($url);
		$infoarray = json_decode($infourl,true);
		//echo'<pre>';
		return $infoarray;
	}
	//获取openid和token
	public function gethurltmls(){
		//这里的scope，snsapi_base是不能获得用户信息的，scope=snsapi_userinfo 才能使用后面的 userinfo接口。
		// $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Fwww.sc.cc%2Fbj%2Fwx%2Fwxtest&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
		$code = $_GET['code'];
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->secret&code=$code&grant_type=authorization_code";
		$htmlu = $this->togetc($url);
    	if($htmlu!="")
    	{
    		$htmls = json_decode($htmlu,true);
    		$access_token = $htmls["access_token"];
    		$openid = $htmls["openid"];
    	}
    	return $htmls;
	}
}