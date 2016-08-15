<?php
namespace Home\Controller;
/**
 * 会员中心控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class VipController extends CommonController {
	protected  $user;	

	public function _initialize(){
		parent::_initialize();
	
		if(empty($_SESSION[C('SESSION_USER_KEY')])){
			$url    =   U($this->city['DOMAIN'].'/login');
			$str    = "<script>var url = '{$url}';window.location.href = url;</script>";
			exit($str);
		}
		$this->user = $_SESSION[C('SESSION_USER_KEY')];
		$this->assign('user',$this->user);
		if($this->user['usertype'] == 1){
			$urls    =   U($this->city['DOMAIN'].'/designerhome');
			redirect($urls);
		}
	}
	
	public function index() {
		//购物车
		$ordermodel = M('orderlist');
		$order = $ordermodel->where(array("userid"=>$this->user['id'],'ordstatus '=>' >0'))->limit(5)->select();
		//预约
		$yymodel = M('reservation_designer');
		$yy = $yymodel->where(array("UID"=>$this->user['id']))->limit(5)->select();
		//案例
		$almodel = M('casedecorate');
		$al = $almodel->where(array("DID"=>$this->user['id']))->limit(6)->select();
		//知识、攻略
		$glmodel = M('tbfitmentguide');
		$gl = $glmodel->where(array("Uid"=>$this->user['id']))->limit(6)->select();
		

		$this->assign('order',$order);
		$this->assign('yy',$yy);
		$this->assign('al',$al);
		$this->assign('gl',$gl);
		$this->assign('title','个人中心-首页');
		$this->assign('title2','会员中心');
		$this->display();
	}
	//文章攻略
	public function article(){
		$model = M('tbfitmentguide');
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		$where['Uid'] = $this->user['id'];
		//判断采用自定义的Model类
		if(!empty($model)) {
			$this->_list($model, $where);
		}
		$this->assign('title','个人中心-我的文章');
		$this->assign('title2','我的文章');
		$gl = $model->where(array("Uid"=>$this->user['id']))->select();
		if(empty($gl)){
			$this->display('articlenone');
		}else{
			$this->display('article');
		}
	}
	//案例
	public function cases(){
		$model = M('casedecorate');
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		$where['DID'] = $this->user['id'];
		//判断采用自定义的Model类
		if(!empty($model)) {
			$this->_list($model, $where);
		}
		$gl = $model->where(array("DID"=>$this->user['id']))->select();
		if(empty($gl)){
			$this->display('casesnone');
		}else{
			$this->display('cases');
		}
	}
	//收藏
	public function collect(){
		$model = M('usercollect');
		if(method_exists($this, '_filter')) {
			$where = $this->_filter();
		}
		$where['uid'] = $this->user['id'];
		//判断采用自定义的Model类
		if(!empty($model)) {
			$this->_list($model, $where);
		}
		$this->display('collect');
	}
	//购物管理
	public function buy(){
		//订单
		$ordermodel = M('orderlist');
		$order = $ordermodel->where(array("userid"=>$this->user['id'],'ordstatus '=>' >0'))->select();
		$this->assign('order',$order);
		
		if(empty($order)){
			$this->display('ordernone');
		}else{
			$this->display('order');
		}
	}
	//订单详情
	public function orderitem(){
    	$id = I('get.id',0);
		$ordermodel = M('orderlist');
		$order = $ordermodel->where(array("userid"=>$this->user['id'],'id'=>$id))->find();

		$this->assign('order',$order);

		$this->display('orderitem');
	}
	//我的订单
    public function myorder(){
    	$id = I('get.id',0);
		$ordermodel = M('orderlist');
		$order = $ordermodel->where(array("userid"=>$this->user['id'],'id'=>$id))->find();
    	$citymodel = M('regionprovince');
		$city = $citymodel->select();
		$this->assign('citys',$city);
		$this->assign('order',$order);
		$this->assign('id',$id);

		$Model = M('regioncounty');
		$cname = $Model
		->field("regioncounty.id xid,regioncity.id sid,regionprovince.id shid,regioncounty.name xname,regioncity.name sname,regionprovince.name shname")
		->join('regioncity ON regioncity.id = regioncounty.cid')
		->join('regionprovince ON regioncity.pid = regionprovince.id')
		->where(array("regioncounty.id"=>$order['cityid']))
		->find();
		$shi = $this->getshi($cname['shid'],$cname['sid']);
		$xian = $this->getxian($cname['sid'],$cname['xid']);
		$this->assign('shi',$shi);
		$this->assign('xian',$xian);
		$this->assign('sheng',$cname['shid']);
		$this->assign('cname',$cname);
		
		$this->display('myorder');
    }
    public function ajaxshi(){
    	$id = I('post.id',0);
		$citymodel = M('regioncity');
		$city = $citymodel->where(array('pid'=>$id))->select();
		$html = '<option value="0">市</option>';
		foreach($city as $k=>$v){
			$html.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
		echo $html;
    }
    public function ajaxxian(){
    	$id = I('post.id',0);
		$citymodel = M('regioncounty');
		$city = $citymodel->where(array('cid'=>$id))->select();
		$html = '<option value="0">县</option>';
		foreach($city as $k=>$v){
			$html.='<option value="'.$v['id'].'">'.$v['name'].'</option>';
		}
		echo $html;
    }
    //提交订单
    function editorder(){
    	$id = I('post.id',0);
    	$address = I('post.address',0);
    	$xian = I('post.xian',0);
    	$info = I('post.info',0);
    	$username = I('post.username',0);
    	$phone = I('post.phone',0);
    	$ordbuynum = I('post.ordbuynum',0);
		$model = M('orderlist');
    	if(!empty($username)){
    		$data['username'] = $username;
    	}
    	if(!empty($phone)){
    		$data['phone'] = $phone;
    	}
    	$data['info'] = $info;
    	$data['address'] = $address;
    	$data['ordbuynum'] = $ordbuynum;
    	$data['cityid'] = $xian;
    	if(!empty($id)){
			$city = $model->where('id='.$id)->save($data);
			$this->success('保存成功！');
    	}else{
    		$this->error('保存失败！');
    	}
    	
    }
    function confirm(){
    	$id = I('get.id',0);
		$ordermodel = M('orderlist');
		$order = $ordermodel->where(array("userid"=>$this->user['id'],'id'=>$id))->find();
		$cname = $this->getcity($order['cityid']);
		$this->assign('cname',$cname);
		$this->assign('order',$order);
		$this->assign('id',$id);
		$this->display('confirm');
		
    }
    function getcity($id){

    	$Model = M('regioncounty');
    	$cname = $Model
    	->field("regioncounty.name xname,regioncity.name sname,regionprovince.name shname")
    	->join('regioncity ON regioncity.id = regioncounty.cid')
    	->join('regionprovince ON regioncity.pid = regionprovince.id')
    	->where(array("regioncounty.id"=>$id))
    	->find();
    	$cityname = $cname["shname"].$cname["sname"].$cname["xname"];
    	return $cityname;
    }
    //生成省市
    function getshi($pid,$id){

    	$Model = M('regioncity');
		$cityname = $Model->where(array("pid"=>$pid))->select();
		$html = '<option value="0">市</option>';
		foreach($cityname as $k=>$v){
			$select = '';
			if($v['id'] == $id){
				$select = 'selected';
			}
			$html.='<option value="'.$v['id'].'" '.$select.'>'.$v['name'].'</option>';
		}
    	return $html;
    }
    //生成省市
    function getxian($pid,$id){

    	$Model = M('regioncounty');
		$cityname = $Model->where(array("cid"=>$pid))->select();
		$html = '<option value="0">市</option>';
		foreach($cityname as $k=>$v){
			$select = '';
			if($v['id'] == $id){
				$select = 'selected';
			}
			$html.='<option value="'.$v['id'].'" '.$select.'>'.$v['name'].'</option>';
		}
    	return $html;
    }
	//优惠管理
	public function youhui(){
		//var_dump($_SESSION);
		//显示优惠券数目
		$this->display('youhui');
	}
	//预约管理
	public function yuyue(){
		//预约
		$yymodel = M('reservation_designer');
		$yy = $yymodel->where(array("UID"=>$this->user['id']))->select();
		$this->assign('yy',$yy);
		if(empty($yy)){
			$this->display('yuyuenone');
		}else{
			$this->display('yuyue');
		}
		
	}
	//消息中心管理
	public function message(){
		$model = M('message');
		$message = $model->where(array("uid"=>$this->user['id']))->select();
		$this->assign('message',$message);
		
		$this->display('message');
	
	}
	//修改资料
	public function setting(){
		var_dump($this->user);
		$this->display('setting');
	}
	//重置密码
	public function password(){
		$this->display('password');
	}
	//重置密码提交
	public function update(){
		$id = $_POST['id'];
		$admin = M("Users");
		$data['userpass'] = md5($_POST['userpass']);
		$data['time'] = date('Y-m-d H:i:s');
		if($admin->where("id=".$id)->save($data)){
			$this->success("重置成功！");
		}else{
			$this->error("重置失败！");
		}
	}
	
}