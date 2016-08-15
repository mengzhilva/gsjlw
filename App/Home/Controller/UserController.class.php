<?php
namespace Home\Controller;/**
 * 会员登录注册等控制器
 * 开发人员：吕定国
 * 开发时间：2015.1.08
 * 修改时间：2015.1.12
 */
class UserController extends CommonController {
    //加载登录页面
    public function login(){
    	$this->assign('isdl',1);
        $this->display();
    }
    public function code(){
    	$Verify = new \Think\Verify();
    	$Verify->entry();
    }
    //执行登录方法
    public function doLogin(){
         
    	$username = I('post.username',0);
    	$userpass = I('post.userpass',0);

    	if(empty($username)){
    			echo "<script>alert('请输入用户名！');history.go(-1);;</script>";
    		exit;
    	}
    	//$code = I('post.code',0);
    	//$Verify = new \Think\Verify();
    	//$check_code = $Verify->check($code);
    	//if(!$check_code){
    		//$this->error('验证码不正确！');
    		//exit;
    	//}
        //根据登录账号获取用户信息
        $user = M("lee_user")->where(array("username"=>$username))->find();
       
        //判断是否获取到用户
        if($user){

            //检测密码：
            if($user['password'] == md5pass($userpass,$user['salt'])){
                //将登录的用户信息放入到session中
                $_SESSION[C('SESSION_USER_KEY')]=$user;   
                //'.$_SERVER['HTTP_REFERER'].' 			
                echo '<script>alert("登录成功！");document.location.href="/index.php/UserCenter/index";</script>';
                
               
            }else{
                // $this->assign("errorinfo","登录密码错误！");
    			echo "<script>alert('密码错误');history.go(-1);;</script>";
                // $this->display("login");
               
            }
        }else{
            // $this->assign("errorinfo","登录账号不存在或被禁用");
    			echo "<script>alert('用户名错误');history.go(-1);;</script>";
            // $this->display("login");
            
        }
    }
    
    //执行退出
    public function logout(){
        unset($_SESSION[C('SESSION_USER_KEY')]);
        $url = '/index.php/index';
        redirect($url);
    }

    public function zhuce(){
    	//var_dump($this->city);
    	$this->assign('isdl',1);
    	$this->display("zhuce");
    }
    protected function sendcode($phone,$Content){
    	
    	$HttpUrl = "http://116.213.72.20/SMSHttpService/send.aspx?username=sczss&password=1jia1=2";;
    	$HttpUrl .=  "&mobile=".$phone;
    	$HttpUrl .=   "&content=".$Content;
    	$HttpUrl .=   "&Extcode=&senddate=&batchID=";
    	$res = file_get_contents($HttpUrl);
    	if($res == 0){
    		echo '成功';
    	}
    	/*-2	提交的号码中包含不符合格式的手机号码
-1	数据保存失败
0	成功
1001	用户名或密码错误
1002	余额不足
1003	参数错误，请输入完整的参数 
1004	其他错误
1005	预约时间格式不正确
*/
    }
	//注册验证码
    public function mcode($phone=''){
    	if(empty($phone))
    	$phone = I('post.phone',0);
    	var_dump($phone);
    	if(empty($phone))
    		echo "<script>alert('手机号为空');;</script>";
    		
    	$code = rand(1000,9999);
			$_SESSION['mcode'] = $code;
			$_SESSION['mtime'] = time();
    		$Content = '验证码为：'.$code;
			//$this->sendcode($phone, $Content);
    		echo "<script>alert('$code');;</script>";
    }
	//忘记密码
    public function findpsw(){

    	$send = I('post.send',0);
	    $phone = I('post.phone',0);
    	if($send === 'send'){
	    	$code = I('post.code',0);
	
	    	if(empty($phone)){
	    		$this->error('请输入手机号！');
	    		exit;
	    	}
	    	$Verify = new \Think\Verify();
	    	$check_code = $Verify->check($code);
	    	if(!$check_code){
	    		$this->error('验证码不正确！');
	    		exit;
	    	}else{
	    		$this->mcode($phone);
	    		$this->success();
	    	}
    	}else{
    		$this->assign('phone',$phone);
    		$this->display("findpsw");
    	}
    }
    //重置密码
    public function reset(){
    	$phone = I('post.phone',0);
    	$code = I('post.mcode',0);
    	if($code != $_SESSION['mcode']){
    		$this->error('验证码不正确');
    		exit;
    	}
    	$this->assign('phone',$phone);
    	$this->assign('code',$code);
    	$this->display("reset");
    }
    //重置密码
    public function resetset(){
    	$phone = I('post.phone',0);
    	$code = I('post.code',0);
    	$userpass = I('post.userpass',0);
    	if($code != $_SESSION['mcode']){
    		$this->error('验证码不正确');
    		exit;
    	}
    	$data['password'] = md5($userpass);
		$res = M('users')->where('mobilephone='.$phone)->save($data);
    	if($res){
    		$this->success('修改成功！');
    	}else{
    		$this->error('修改失败');
    	}
    }
    //验证码验证
    function check_yzm(){

    	$code = I('post.mcode',0);
    	if($code != $_SESSION['mcode']){
    		$this->error('验证码不正确');
    		exit;
    	}else{
    		$this->success('');
    	}
    	
    }
    
    //用户插入数据库操作
    public function insert(){
    	$email = I('post.email',0);
    	$userpass = I('post.userpass',0);
    	$username = I('post.username',0);
    	$reg = '/^(([a-z]*[0-9]*)|([0-9]*[a-z]*))[a-z0-9]*$/';
    	if(empty($username)){
    		echo "<script>alert('用户名是必填的哦！');history.go(-1);</script>";
    		//$this->error('邮箱是必填的哦！');
    		exit;
    	}
    	if(!preg_match($reg,$username)){
    		echo "<script>alert('用户名是必填是英文字母或者数字');history.go(-1);</script>";
    		exit;
    	}
    	if(empty($email)){
    		echo "<script>alert('邮箱是必填的哦！');history.go(-1);</script>";
    		//$this->error('邮箱是必填的哦！');
    		exit;
    	}
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if ( preg_match( $pattern, $email ) ){
    		echo "<script>alert('邮箱的格式不对！');history.go(-1);</script>";
    		//$this->error('邮箱是必填的哦！');
    		exit;
    	}
    	if($userpass !== I('post.ruserpass',0)){
    		echo "<script>alert('密码不一致');history.go(-1);</script>";
    		//$this->error('密码不一致');
    		exit;
    	}
    	$check = M("lee_user")->where("username='".$username."'")->select();
    	if(!empty($check)){
    		echo "<script>alert('用户名已注册！');history.go(-1);</script>";
    		//$this->error('用户名已注册！');
    		exit;
    	}
    	//$code = I('post.mcode',0);
    	
    	/*if(isset($_SESSION['mtime']) && ($_SESSION['mtime']+360)<time()){
    		session_destroy();
    		unset($_SESSION);
    		header('content-type:text/html; charset=utf-8;');
    		echo '<script>alert("验证码已过期，请重新获取！");</script>';
    		exit;
    	}else{ 
    		//if($code != $_SESSION['mcode']){
    		//$this->error('验证码不正确');
    		//exit;
    	}*/
    	$data['username'] = $username;
    	$data['email'] = $email;
    	$data['password'] = md5pass($userpass,'fzkqzB');
    	$data['createtime'] = date('Y-m-d H:i:s');
    	$data['updatetime'] = date('Y-m-d H:i:s');
    	$data['lasttime'] = date('Y-m-d H:i:s');
    	$data['usergroup'] = 7;
    	$data['regip'] = $this->getip();
		$data['lastip'] = $this->getip();
    	$UsersArray = M("lee_user");
    	//var_dump($data);exit;
    	$insertid  = $UsersArray->add($data);
    	//$aa = $UsersArray->getLastSql();
    	//var_dump($aa);exit;
    	if($insertid){
        		$user = M("lee_user")->where(array("id"=>$insertid))->find();
    			$_SESSION[C('SESSION_USER_KEY')]=$user;
    			echo '<script>alert("注册成功！");document.location.href="/index.php/UserCenter/index";</script>';
    		} else {
    			echo "<script>alert('添加失败');history.go(-1);</script>";
    		}
    }
}