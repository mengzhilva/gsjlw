<?php
namespace Home\Controller;
/**
 * 微信接口控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class WxCommonController extends CommonController {
    protected $appid;
    protected $secret;
    public $topid;
    public $wxuserinfo;
    public function _initialize(){
        parent::_initialize();
        /**s微信转发的就是调用参数 
         * 
        * **/
        $this->appid = 'wx6ff0ede1fce6d885';
        $this->secret = '079026d605e407b449d0433590e91da0';
      	vendor('jsdk.jssdk');
		$jssdk = new \JSSDK("wx6ff0ede1fce6d885", "079026d605e407b449d0433590e91da0");
		$signPackages = $jssdk->GetSignPackage();
		//var_dump($signPackages);exit;
		$this->assign("signPackages",$signPackages);
		$homeurl = $_SERVER['SERVER_NAME'];

		$this->assign("isygz",0);
		//var_dump(ACTION_NAME);exit;
		
		/**转发逻辑 
		 * 用户之间转发带推广员id ，推广员之间转发带最后的id
		 * **/
		$topid = I('get.topid',0);//推广员的id
		//var_dump($topid);
		$uid = I('get.uid',0);//微信关注后点过了的用户
		//如果没有用户的session则跳转
		$action = ACTION_NAME;
		//var_dump($_SERVER);EXIT;
		//不用跳转在控制器
		$_SESSION[C('SESSION_USER_KEY')];
		if(empty($_SESSION[C('SESSION_USER_KEY')])){
			$openid = $this->getopenid();
			if(!empty($openid)){
				$user = M('lee_user')->where("openid='".$openid."'")->find();
				if(!empty($user)){
					$_SESSION[C('SESSION_USER_KEY')] = $user;
				}else{
					$data['openid'] = $openid;
			    	$data['username'] = 'wx';
			    	$data['email'] = '';
			    	$userpass = '123456';
			    	$data['password'] = md5pass($userpass,'fzkqzB');
			    	$data['createtime'] = date('Y-m-d H:i:s');
			    	$data['updatetime'] = date('Y-m-d H:i:s');
			    	$data['lasttime'] = date('Y-m-d H:i:s');
			    	$data['usergroup'] = 7;
			    	$data['regip'] = $this->getip();
					$data['lastip'] = $this->getip();
    				$insertid  = M('lee_user')->add($data);
				}
			}
		}

		$returnurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$returnurl = urlencode($returnurl);
		header('location:'.$returnurl);
		//var_dump($returnurl);EXIT;
		$homeurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
		//header('location:'.$homeurl);
		
    }


    public function getopenid(){//获取用户openid简单

		$action = ACTION_NAME;
		//var_dump($_SERVER);EXIT;
		//不用跳转在控制器
		$actionarr = ['register'];
		if(!in_array($action, $actionarr) && empty($_SERVER['wxinfo'])){
	        //$url =https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri=http%3A%2F%2Fwww.sc.cc%2Fbj%2FWxCommon%2Fgetopenid&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
	        $code = $_GET['code'];
	        //var_dump($code);
	        //var_dump($code);
	        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->secret&code=$code&grant_type=authorization_code";
	        $htmlu = $this->togetc($url);
	        if($htmlu!="")
	        {
	            $htmls = json_decode($htmlu,true);
	            $access_token = $htmls["access_token"];
	            $openid = $htmls["openid"];
	        }
	    }
	        return  $openid;
        //$infourl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        //$htmli = $this->togetc($infourl);
        //var_dump($htmli);
    }
    function updatewxuser($wxinfo){

    	$data['nickname'] = $wxinfo['nickname'];
    	$data['sex'] = $wxinfo['sex'];
    	$data['province'] = $wxinfo['province'];
    	$data['city'] = $wxinfo['city'];
    	$data['country'] = $wxinfo['country'];
    	$data['headimgurl'] = $wxinfo['headimgurl'];
    	$data['intime'] = date('Y-m-d H:i:s');
    	M('weixinuserlog')->where("openid='".$wxinfo['openid']."'")->save($data);
    	$_SESSION['wxinfo'] = M('weixinuserlog')->where("openid='".$wxinfo['openid']."'")->find();
    }
    //推广员推广二维码
    public function gettgerwm($id = 0){//推广员公众号二维码生成
    	if(empty($id)){
    		$id = I('get.id',0);
    	}
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
	
		//echo $img;exit;
	    	header('Location: https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket);
		}
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
		$openid = $htmls['openid'];
		$access_token = $htmls["access_token"];
		//获取用户信息
		$url ="https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
		$infourl = $this->togetc($url);
		$infoarray = json_decode($infourl,true);
		//echo'<pre>';
		return $infoarray;
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
     *  作用：以post方式提交到对应的接口url
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

}
?>