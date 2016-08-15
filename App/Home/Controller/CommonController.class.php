<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 公共Action
 *
 */
class CommonController extends Controller{
    protected $city;
    protected $PromoterId;
    protected $cityname;
    //在Controller类中构造方法执行后则会自动调用的方法。
    public function _initialize(){
    	$this->assign('imgurl',C('IMGURL'));
		
        $this->appid = 'wx6ff0ede1fce6d885';
        $this->secret = '079026d605e407b449d0433590e91da0';
      	vendor('jsdk.jssdk');
		$jssdk = new \JSSDK("wx6ff0ede1fce6d885", "079026d605e407b449d0433590e91da0");
		$signPackages = $jssdk->GetSignPackage();
		//var_dump($signPackages);exit;
		$this->assign("signPackages",$signPackages);
		
		$returnurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$returnurl = urlencode($returnurl);
		//header('location:'.$returnurl);
		//var_dump($returnurl);EXIT;
		$fxurl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx6ff0ede1fce6d885&redirect_uri='.$returnurl.'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';

		$this->assign('fxurl',$fxurl);
		
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
		

    }
    public function getopenid(){//获取用户openid简单

		$action = ACTION_NAME;
		//var_dump($_SERVER);EXIT;
		//不用跳转在控制器
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
	        return  $openid;
        //$infourl = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        //$htmli = $this->togetc($infourl);
        //var_dump($htmli);
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
    //定位城市

    function GetIpLookup($ip = ''){
    	if(empty($ip)){
    		$ip = $this->getip();
    	}
    	$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    	if(empty($res)){ return false; }
    	$jsonMatches = array();
    	preg_match('#\{.+?\}#', $res, $jsonMatches);
    	if(!isset($jsonMatches[0])){ return false; }
    	$json = json_decode($jsonMatches[0], true);
    	if(isset($json['ret']) && $json['ret'] == 1){
    		$json['ip'] = $ip;
    		unset($json['ret']);
    	}else{
    		return false;
    	}
    	return $json;
    }
    
	//访问量方法
	protected function vistlog($table,$visitfield,$id,$idfield){
		$ip = $this->getip();
		if(!empty($ip)){
			$ipmodel = M('visitlogo');
            $where['ip']=$ip;
            $where['did']=$id;
			$visit = $ipmodel->where($where)->find();
			$date = date('Y-m-d');
			$model = M($table);
            $data['ip'] = $ip;
            $data['dates'] = $date;
            $data['did'] = $id;
			if(empty($visit)){
				$ipmodel->add($data);
				//更新访问量
				$model->query("update $table set $visitfield=$visitfield+1 where $idfield=$id");
			}else{
				if($visit['dates'] !== $date && $visit['did']!==$id){ 
					//更新访问量
					$model->query("update $table set $visitfield=$visitfield+1 where $idfield=$id");
					$ipmodel->where(array('id'=>$visit['id']))->save($data);

				}
			}

		}
	}
    function getip(){
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $online_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
        $online_ip = $_SERVER['HTTP_CLIENT_IP'];
    }else{
        $online_ip = $_SERVER['REMOTE_ADDR'];
    }
    return $online_ip;
}
	// protected function getip() {
		
 //        if (isset($_SERVER)) 
 //        {
 //                if (isset($_SERVER[HTTP_X_FORWARDED_FOR]) && strcasecmp($_SERVER[HTTP_X_FORWARDED_FOR], "unknown"))//代理
 //                {
 //                        $realip = $_SERVER[HTTP_X_FORWARDED_FOR];
 //                } 
 //                elseif(isset($_SERVER[HTTP_CLIENT_IP]) && strcasecmp($_SERVER[HTTP_CLIENT_IP], "unknown"))
 //                {
 //                        $realip = $_SERVER[HTTP_CLIENT_IP];
 //                } 
 //                elseif(isset($_SERVER[REMOTE_ADDR]) && strcasecmp($_SERVER[REMOTE_ADDR], "unknown"))
 //                {
 //                        $realip = $_SERVER[REMOTE_ADDR];
 //                } 
 //                else
 //                {
 //                        $realip = '';
 //                }
 //        } 
 //        else
 //        {
 //                if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
 //                {
 //                        $realip = getenv("HTTP_X_FORWARDED_FOR");
 //                }
 //                elseif(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
 //                {
 //                        $realip = getenv("HTTP_CLIENT_IP");
 //                } 
 //                elseif(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
 //                {
 //                        $realip = getenv("REMOTE_ADDR");
 //                } 
 //                else
 //                {
 //                        $realip = '';
 //                }
 //        } 
 //        return $realip;
	// }
	protected function jump(){
		$arr = array('nanchang.sc.cc'=>'www.sc.cc/nc','hangzhou.sc.cc'=>'www.sc.cc/hz','xining.sc.cc'=>'www.sc.cc/xn','changchun.sc.cc'=>'www.sc.cc/cc','shanghai.sc.cc'=>'www.sc.cc/sh','guangzhou.sc.cc'=>'www.sc.cc/gz','hefei.sc.cc'=>'www.sc.cc/gf','chengdu.sc.cc'=>'www.sc.cc/cd','hanxdan.sc.cc'=>'www.sc.cc/hd','lanzhou.sc.cc'=>'www.sc.cc/lz','changsha.sc.cc'=>'www.sc.cc/cs','taiyuan.sc.cc'=>'www.sc.cc/ty','jinan.sc.cc'=>'www.sc.cc/jn','xian.sc.cc'=>'www.sc.cc/xa','wuhan.sc.cc'=>'www.sc.cc/wh','nanjing.sc.cc'=>'www.sc.cc/nj','yinchuan.sc.cc'=>'www.sc.cc/yc','haerbin.sc.cc'=>'www.sc.cc/heb','huhehaote.sc.cc'=>'www.sc.cc/hhht','yantai.sc.cc'=>'www.sc.cc/yt','qingdao.sc.cc'=>'www.sc.cc/qd','shijiazhuang.sc.cc'=>'www.sc.cc/sjz','shenyang.sc.cc'=>'www.sc.cc/sy','qinhuangdao.sc.cc'=>'www.sc.cc/qhd','tangshan.sc.cc'=>'www.sc.cc/ts','zhengzhou.sc.cc'=>'www.sc.cc/zz','tianjin.sc.cc'=>'www.sc.cc/tj');
		$server = $_SERVER['SERVER_NAME'];
		if($server!='www.sc.cc'){

			Header("HTTP/1.1 301 Moved Permanently");
			Header("Location: http://".$arr[$server]);
		}
	}
	protected function bmadd($data){
		$data = $this->objectToArray($data);
		$model = M("reservation_construction");
		$datas = array();
		foreach($data as $k=>$v){
			$v = strip_tags($v);
			$datas[$k] = check_input($v);
		}
		$data = $datas;
		//是否已报名
		$isbm = $model->where(array('PHONE'=>$data['PHONE'],'TYPE'=>$data['TYPE']))->find();
		if(!empty($isbm)){
			return 0;exit;
		}
		$now = date('Y-m-d H:i:s');
		$ip = $this->getip();
		$history = $model->where(array('ip'=>$ip))->order('addtime desc')->find();
		if(!empty($history)){
			$historytime = strtotime($history['addtime']);
			if((time()-$historytime)<5){
				return 0;exit;
			}
		}
		if($data['NAME'] == '88888'){
			return 0;exit;
		}
		$data['ip'] = $this->getip();
		$data['addtime'] = $now;
		$id = $model->add($data);
		return $id;
	}
    public function index() {
        //列表过滤器，生成查询Map对象
        $map = $this->_search();
        if(method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        //判断采用自定义的Model类
        if(!defined(CONTROLLER_NAME)){
           $model = D(CONTROLLER_NAME);
        }
        
        if(!empty($model)) {
            $this->_list($model, $map);
        }
        $this->display();
        return;
    }

        /**
     * 根据表单生成查询条件
     * 进行列表过滤
     * @param string $name 数据对象名称
     * @return HashMap
     */
    protected function _search($name='') {
        //生成查询条件
        if (empty($name)) {
            $name = CONTROLLER_NAME;
        }
        $model = M($name);
        $map = array();
        foreach ($model->getDbFields() as $key => $val) {
            if (substr($key, 0, 1) == '_')
                continue;
            if (isset($_REQUEST[$val]) && $_REQUEST[$val] != '') {
                $map[$val] = $_REQUEST[$val];
            }
        }
        return $map;
    }
    
    /**
     * 根据表单生成查询条件
     * 进行列表过滤
     * @param Model $model 数据对象
     * @param HashMap $map 过滤条件
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
     */
    protected function _list($model, $map = array(), $sortBy = '', $asc = false, $pnum='') {
        
        
        //排序字段 默认为主键名
        if (!empty($_REQUEST['_order'])) {
            $order = $_REQUEST['_order'];
        } else {
            $order = !empty($sortBy)?$sortBy:$model->getPk();
        }
        
        //排序方式默认按照倒序排列
        //接受 sort参数 0 表示倒序 非0都 表示正序
        if (!empty($_REQUEST['_sort'])) {
            $sort = $_REQUEST['_sort'] == 'asc'?'asc':'desc';
        } else {
            $sort = $asc ? 'asc' : 'desc';
        }
        
        //取得满足条件的记录数
        $count = $model->where($map)->count();
        //每页显示的记录数
        if (!empty($_REQUEST['numPerPage'])) {
            $listRows = $_REQUEST['numPerPage'];
        } else if(!empty($pnum)){$listRows = $pnum;}else{
            $listRows = '10';
        }
        
        
        //设置当前页码
        if(!empty($_REQUEST['p'])) {
            $nowPage=$_REQUEST['p'];
        }else{
            $nowPage=1;
        }
        $_GET['p']=$nowPage;
        
        //创建分页对象
        $p = new \Think\Page($count, $listRows);
        
        
        //分页查询数据
        $list = $model->where($map)->order($order.' '.$sort)
                        ->limit($p->firstRow.','.$p->listRows)
                        ->select();
                        
        //回调函数，用于数据加工，如将用户id，替换成用户名称
        if (method_exists($this, '_tigger_list')) {
            $this->_tigger_list($list);
        }
        
        
        //分页跳转的时候保证查询条件
        //foreach ($map as $key => $val) {
            //if (!is_array($val)) {
                //$p->parameter .= "$key=" . urlencode($val) . "&";
            //}
       // }
    
        //分页显示
        $page = $p->show($this->city['DOMAIN']);
        
        //列表排序显示
        $sortImg = $sort;                                 //排序图标
        $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列';   //排序提示
        $sort = $sort == 'desc' ? 1 : 0;                  //排序方式
        
        //模板赋值显示
        $this->assign('list', $list);
        $this->assign('sort', $sort);
        $this->assign('order', $order);
        $this->assign('sortImg', $sortImg);
        $this->assign('sortType', $sortAlt);
        $this->assign("page", $page);
        
        $this->assign("search",         $search);           //搜索类型
        $this->assign("values",         $_POST['values']);  //搜索输入框内容
        $this->assign("totalCount",     $count);            //总条数
        $this->assign("numPerPage",     $p->listRows);      //每页显多少条
        $this->assign("currentPage",    $nowPage);          //当前页码

    }
	protected function entry($data ,$id=0){
		$data = $this->objectToArray($data);
		$user = array();
		if(empty($data['uid'])){
			$user = M("users")->where(array('mobilephone'=>$data['PHONE']))->find();
			if(empty($user)){
				$pass = rand(111111,999999);
				$datas['mobilephone'] = $data['PHONE'];
				$datas['username'] = $data['NAME'];
				$datas['registerTime'] = date('Y-m-d H:i:s');
				$datas['password'] = MD5($pass);
				$datas['CID'] = $data['CID'];
				$datas['Promoterid'] = $data['PromoterId'];
				$id =  M("users")->add($datas);
				$user = M("users")->where(array('mobilephone'=>$data['PHONE']))->find();
			}
		}
      $_SESSION[C('SESSION_USER_KEY')]=$user;
      //短信发送
      $msg = '【实创装饰】您好，您在实创官网（www.sc.cc）的登陆名：'.$data['PHONE'].'，密码是：'.$pass.'，请您妥善保管。整体家装688元/㎡详询实创官网。';
      sendPhonePro($datas['mobilephone'],$msg);
      return $user;
	}
	protected function objectToArray($e){
		$e=(array)$e;
		foreach($e as $k=>$v){
			if( gettype($v)=='resource' ) return;
			if( gettype($v)=='object' || gettype($v)=='array' )
				$e[$k]=(array)objectToArray($v);
		}
		return $e;
	}

}
?>