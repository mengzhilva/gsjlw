<?php
namespace Home\Controller;
use Think\Controller;
/**
 * 活动控制器
 * 开发人员：吕定国
 * 开发时间：2014.12.08
 * 修改时间：2015.1.12
 */
class HdController extends Controller {
	private $hd;
	public function __construct(){
		parent::__construct();
		$this->hd =M('tbclassroom');
		$this->hdbm = M('tbclassroomorder');
		
		
	}
    public function index(){
    	$id = I('get.id',0);
    	$id = intval($id);
    	//var_dump($id);
    	$info = $this->hd->query("select *  from tbClassRoom where Ism=1 and ID=".$id);

    	if(!empty($info[0]['LinkUrl'])){
    		header('Location:'.$info[0]['LinkUrl']);exit;
    	}
    	 
    	$chajians=$this->hd->query("select * from tbClassRoomChajian as a left join tbClassChajian as b on a.chajianID=b.id  where a.hdID=".$id);
    	
    	$this->assign('utm_source',I('get.utm_source',0));
    	$this->assign('utm_term',I('get.utm_term',0));
    	$this->assign('Promoterid',I('get.Promoterid',0));
		$PromoterID = I('get.Promoterid',0);
    	$PromoterNames = $this->hd->query("select *  from tbPromoter where id=".$PromoterID);
    	//var_dump($PromoterNames);
    	$PromoterName = $PromoterNames[0]['PromoterName'];
		if(empty($info[0]['CityID']))
			$info[0]['CityID']=1;
    	$cityinfo = $this->hd->query("select *  from city where id=".$info[0]['CityID']);
		if(!empty($PromoterID)){
    		$cityinfo[0]['LEYUFID'] = $Promoter['leyufid'];
    		$cityinfo[0]['DOMAIN'] = $city['DOMAIN'].'_'.$PromoterID;
    		$cityinfo[0]['TELEPHONE'] = $PromoterNames[0]['PromoterTel'];
			
		}
    	//$info[0]['hdld'] = str_replace('/n', '<br/>', $info[0]['hdld']);
    	$this->assign('cityinfo',$cityinfo[0]);
    	$this->assign('PromoterName',$PromoterName);
    	$this->assign('list',$info[0]);
    	$this->assign('id',$id);
    	$this->assign('chajians',$chajians);
    	//var_dump($chajians);
        $this->display();
    }
    public function bm(){
   		$UPDATETIME = date('Y-m-d H:i:s');
    	@set_magic_quotes_runtime(0);
    	
		$username = $this->stripslashes_array(I('POST.UserName',0));
		$TelePhone = $this->stripslashes_array(I('post.TelePhone',0));
		$ClassRoomID = intval(I('post.ClassRoomID',0));
		$ClassRoomName = $this->stripslashes_array(I('post.ClassRoomName',0));
		$CityID = intval(I('post.CityID',0));
		$city = M('city')->where('ID='.$CityID)->select();
		$CityName = $city[0]['NAME'];
		
		$Sex = "先生";
		$LpName = $this->stripslashes_array(I('post.LpName',0));
		$LpNamem = $this->stripslashes_array(I('post.LpNamem',0));
		
		$qudao = $this->stripslashes_array(I('post.qudao',0));
		$utm_term = $this->stripslashes_array(I('post.utm_term',0));
		$Promoterid = intval(I('post.Promoterid',0));
		$PromoterName = $this->stripslashes_array(I('post.PromoterName',0));
		
		$UserIp = $_SERVER["REMOTE_ADDR"];
		$insertsql = "insert into tbClassRoomOrder  (UserIp,qudao,utm_term,Promoterid,PromoterName,ClassRoomID,ClassRoomName,UserName,TelePhone,LpName,Sex,CityID,CityName,nums,UpdateTime) values (";
		$insertsql = $insertsql . "'$UserIp',";
		$insertsql = $insertsql . "'$qudao',";
		$insertsql = $insertsql . "'$utm_term',";
		$insertsql = $insertsql . "'$Promoterid',";
		$insertsql = $insertsql . "'$PromoterName',";
		$insertsql = $insertsql . "$ClassRoomID,";
		$insertsql = $insertsql . "'$ClassRoomName',";
		$insertsql = $insertsql . "'$username',";
		$insertsql = $insertsql . "'$TelePhone',";
		$insertsql = $insertsql . "'$LpName',";
		$insertsql = $insertsql . "'$Sex',";
		$insertsql = $insertsql . "'$CityID',";
		$insertsql = $insertsql . "'$CityName',1,";
		$insertsql = $insertsql . "'$UPDATETIME')";
		//var_dump($insertsql);
		$this->hdbm->query($insertsql);
		$this->hdbm->query("update tbclassroom set Nums=Nums+1 where ID=$ClassRoomID");
    	echo wscript("alert('报名成功');history.go(-1);");
    	exit();
    }

    function stripslashes_array($array) {
    	if (is_array($array)) {
    		foreach ($array as $k => $v) {
    			$array[$k] = stripslashes_array($v);
    		}
    	} else if (is_string($array)) {
    		$array = stripslashes($array);
    		$array = strip_tags($array);
    	}
    	return $array;
    }
    //热点链接弹出页
    function link(){
    	$id = I('get.id',0);
    	$id = intval($id);
    	//var_dump($id);
    	$info = $this->hd->query("select *  from tbClassRoom where ID=".$id);
    	
    	$this->assign('utm_source',I('get.utm_source',0));
    	$this->assign('utm_term',I('get.utm_term',0));
    	$this->assign('Promoterid',I('get.Promoterid',0));
    	$PromoterID = I('get.Promoterid',0);
    	$PromoterNames = $this->hd->query("select *  from tbPromoter where id=".$PromoterID);
    	//var_dump($PromoterNames);
    	$PromoterName = $PromoterNames[0]['PromoterName'];
    	$this->assign('PromoterName',$PromoterName);
    	$this->assign('list',$info[0]);
    	$this->assign('id',$id);
    	//var_dump($chajians);
    	$this->display('link');
    }
    function sendView(){
    	$id = I('get.id',0);
    	$id = intval($id);
    	//var_dump($id);
    	$info = $this->hd->query("select *  from tbClassRoom where ID=".$id);
    	
    	$this->assign('utm_source',I('get.utm_source',0));
    	$this->assign('utm_term',I('get.utm_term',0));
    	$this->assign('Promoterid',I('get.Promoterid',0));
    	$PromoterID = I('get.Promoterid',0);
    	$PromoterNames = $this->hd->query("select *  from tbPromoter where id=".$PromoterID);
    	//var_dump($PromoterNames);
    	$PromoterName = $PromoterNames[0]['PromoterName'];
    	$this->assign('PromoterName',$PromoterName);
    	$this->assign('list',$info[0]);
    	$this->assign('id',$id);
    	//var_dump($chajians);
    	$this->display('send');
    	
    }
    
    //发送到手机
    function send(){
    	$id = I('post.ClassRoomID',0);

    	$UPDATETIME = date('Y-m-d H:i:s');
    	@set_magic_quotes_runtime(0);
    	 
    	$username = $this->stripslashes_array(I('POST.UserName',0));
    	$TelePhone = $this->stripslashes_array(I('post.TelePhone',0));
    	$ClassRoomID = intval(I('post.ClassRoomID',0));
    	$ClassRoomName = $this->stripslashes_array(I('post.ClassRoomName',0));
    	$CityID = intval(I('post.CityID',0));
    	$city = M('city')->where('ID='.$CityID)->select();
    	$CityName = $city[0]['NAME'];
    	
    	$Sex = I('post.Sex',0);
    	$LpName = $this->stripslashes_array(I('post.LpName',0));
    	$LpNamem = $this->stripslashes_array(I('post.LpNamem',0));
    	
    	$qudao = $this->stripslashes_array(I('post.qudao',0));
    	$utm_term = $this->stripslashes_array(I('post.utm_term',0));
    	$Promoterid = intval(I('post.Promoterid',0));
    	$PromoterName = $this->stripslashes_array(I('post.PromoterName',0));
    	
    	$UserIp = $_SERVER["REMOTE_ADDR"];
    	$insertsql = "insert into tbClassRoomOrder  (UserIp,qudao,utm_term,Promoterid,PromoterName,ClassRoomID,ClassRoomName,UserName,TelePhone,LpName,Sex,CityID,CityName,nums,UpdateTime) values (";
    	$insertsql = $insertsql . "'$UserIp',";
    	$insertsql = $insertsql . "'$qudao',";
    	$insertsql = $insertsql . "'$utm_term',";
    	$insertsql = $insertsql . "'$Promoterid',";
    	$insertsql = $insertsql . "'$PromoterName',";
    	$insertsql = $insertsql . "$ClassRoomID,";
    	$insertsql = $insertsql . "'$ClassRoomName',";
    	$insertsql = $insertsql . "'$username',";
    	$insertsql = $insertsql . "'$TelePhone',";
    	$insertsql = $insertsql . "'$LpName',";
    	$insertsql = $insertsql . "'$Sex',";
    	$insertsql = $insertsql . "'$CityID',";
    	$insertsql = $insertsql . "'$CityName',1,";
    	$insertsql = $insertsql . "'$UPDATETIME')";
    	//var_dump($insertsql);
    	$this->hdbm->query($insertsql);
    	
    	$Mobile = '15110012192';//I('post.TelePhone');
    	$info = $this->hd->query("select *  from tbClassRoom where ID=".$id);
    	//$info[0]['Title']
    	//推广员信息
    	$PromoterNames = $this->hd->query("select *  from tbPromoter where id=".$Promoterid);
    	$PromoterTel = $PromoterNames[0]['PromoterTel'];
    	$phone = '400-03-28800';
    	if(!empty($PromoterTel)){
    		$phone = $PromoterTel;
    	}
    	$Content = '尊敬的‘'.$username.'’先生您好，'.$info[0]['phonecontent'].'，详情：'.$phone;
    	$HttpUrl = "http://125.208.3.91:8888/sms.aspx?action=send&userid=7080&account=SC1&password=sc51567769";
    	$HttpUrl .=  "&Mobile=".$Mobile;
    	$HttpUrl .=   "&Content=".$Content;
    	$HttpUrl .=   "&sendTime=&extno=";
    	$res = file_get_contents($HttpUrl);
    	$xml = simplexml_load_string($res);
    	if($xml->message == 'ok'){
    		echo wscript("alert('发送成功');history.go(-1)");
    		exit;
    	}else{

    		echo wscript("alert('发送失败');history.go(-1);");
    		exit;
    	}
    }
	public function gd(){

		$id = I('get.id',0);
		$id = intval($id);
		$height = I('get.h',0);

		$this->assign('height',$height);
		$this->assign('id',$id);
		$this->display('gd');
	}
	public function ajaxget(){

		$id = I('post.id',0);
		$id = intval($id);
		//
		$list = $this->hd->query("select *  from tbClassRoomOrder where ClassRoomId=".$id);
		
		$html = '';
		if(count($list)<50){
		
			foreach($list as $k=>$v){
				$time = date('m月d日',strtotime($v['UpdateTime']));
				$html .='<li>'.$v['UserName'].$v['Sex'].'('.$time.'已申请成功) </li>';
			}
			foreach($list as $k=>$v){
				$time = date('m月d日',strtotime($v['UpdateTime']));
				$html .='<li>'.$v['UserName'].$v['Sex'].'('.$time.'已申请成功) </li>';
			}
			foreach($list as $k=>$v){
				$time = date('m月d日',strtotime($v['UpdateTime']));
				$html .='<li>'.$v['UserName'].$v['Sex'].'('.$time.'已申请成功) </li>';
			}
		}else{
			foreach($list as $k=>$v){
				$time = date('m月d日',strtotime($v['UpdateTime']));
				$html .='<li>'.$v['UserName'].$v['Sex'].'('.$time.'已申请成功) </li>';
			}
		}
		echo $html;
		//$this->load->view('welcome_message');
		
		
	}
	
	public function ajaxgetbmnumber(){

		$id = I('get.id',0);
		$id = intval($id);
		//
		$list = $this->hd->query("select count(1) as num  from tbClassRoomOrder where ClassRoomId=".$id);
		if(!empty($list)){
			echo $list[0]['num'];
		}else{
			echo 0;
		}
	}
}