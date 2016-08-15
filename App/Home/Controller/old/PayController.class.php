<?php
//自定义用户信息控制器管理
namespace Home\Controller;
use Think\Controller;
// use Think\Controller;
// +----------------------------------------------------------------------
// | * 开发人员：葛荣
// +----------------------------------------------------------------------
// | * 开发时间：2015年1月5日
// +----------------------------------------------------------------------
// | * 修改时间：2014年1月5日
// +----------------------------------------------------------------------
// | * 页面说明：支付宝支付接口
// +----------------------------------------------------------------------
class PayController extends Controller {
    // 在类初始化方法中，引入相关类库
     public function  __construct(){
      parent::__construct();
      vendor('Alipay.Corefunction');
      vendor('Alipay.Md5function');
      vendor('Alipay.Notify');
      vendor('Alipay.Submit');
    }
    public function index(){
      $this->display('index');
    }
    //doalipay方法
    public function doalipay(){

      $alipay_config=C('alipay_config');
    	//这里我们通过TP的C函数把配置项参数读出，赋给$alipay_config；
        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "Pay/notifyurl";
        //需http://格式的完整路径，不能加?id=123这类自定义参数        //页面跳转同步通知页面路径
        $return_url = "Pay/returnurl";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/        //卖家支付宝帐户
        $seller_email = $_POST['WIDseller_email'];
        //必填        //商户订单号
        $out_trade_no = $_POST['WIDout_trade_no'];
        //商户网站订单系统中唯一订单号，必填        //订单名称
        $subject = $_POST['WIDsubject'];
        //必填        //付款金额
        $total_fee = $_POST['WIDtotal_fee'];
        //必填        //订单描述        $body = $_POST['WIDbody'];
        //商品展示地址
        $show_url = $_POST['WIDshow_url'];
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1

         //构造要请求的参数数组，无需改动
     	$parameter = array(
        "service" => "create_direct_pay_by_user",
        "partner" => trim($alipay_config['partner']),
        "payment_type"    => $payment_type,
        "notify_url"    => $notify_url,
        "return_url"    => $return_url,
        "seller_email"    => $seller_email,
        "out_trade_no"    => $out_trade_no,
        "subject"    => $subject,
        "total_fee"    => $total_fee,
        "body"            => $body,
        "show_url"    => $show_url,
        "anti_phishing_key"    => $anti_phishing_key,
        "exter_invoke_ip"    => $exter_invoke_ip,
        "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
        echo $html_text;
    }

    function notifyurl(){
    	//这里还是通过C函数来读取配置项，赋值给$alipay_config
        $alipay_config=C('alipay_config');
        //计算得出通知验证结果
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
           //验证成功
           //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
           $out_trade_no   = $_POST['out_trade_no'];      //商户订单号
           $trade_no       = $_POST['trade_no'];          //支付宝交易号
           $trade_status   = $_POST['trade_status'];      //交易状态
           $total_fee      = $_POST['total_fee'];         //交易金额
           $notify_id      = $_POST['notify_id'];         //通知校验ID。
           $notify_time    = $_POST['notify_time'];       //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
           $buyer_email    = $_POST['buyer_email'];       //买家支付宝帐号；
           $parameter = array(
             "out_trade_no"     => $out_trade_no, //商户订单编号；
             "trade_no"     => $trade_no,     //支付宝交易号；
             "total_fee"     => $total_fee,    //交易金额；
             "trade_status"     => $trade_status, //交易状态
             "notify_id"     => $notify_id,    //通知校验ID。
             "notify_time"   => $notify_time,  //通知的发送时间。
             "buyer_email"   => $buyer_email,  //买家支付宝帐号；
           );
           if($_POST['trade_status'] == 'TRADE_FINISHED') {
                       //
           }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
           	 if(!checkorderstatus($out_trade_no)){
               orderhandle($parameter);
                           //进行订单处理，并传送从支付宝返回的参数；
               }
            }
                echo "success";        //请不要修改或删除
         }else {
                //验证失败
                echo "fail";
        }
    }

    function returnurl(){
    	 //头部的处理跟上面两个方法一样，这里不罗嗦了！
        $alipay_config=C('alipay_config');
        $alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
        $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
        $trade_no       = $_GET['trade_no'];          //支付宝交易号
        $trade_status   = $_GET['trade_status'];      //交易状态
        $total_fee      = $_GET['total_fee'];         //交易金额
        $notify_id      = $_GET['notify_id'];         //通知校验ID。
        $notify_time    = $_GET['notify_time'];       //通知的发送时间。
        $buyer_email    = $_GET['buyer_email'];       //买家支付宝帐号；

        $parameter = array(
            "out_trade_no"     => $out_trade_no,      //商户订单编号；
            "trade_no"     => $trade_no,          //支付宝交易号；
            "total_fee"      => $total_fee,         //交易金额；
            "trade_status"     => $trade_status,      //交易状态
            "notify_id"      => $notify_id,         //通知校验ID。
            "notify_time"    => $notify_time,       //通知的发送时间。
            "buyer_email"    => $buyer_email,       //买家支付宝帐号
        );
	        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
			//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
	    	}else {
	      		echo "trade_status=".$_GET['trade_status'];
	    	}
			echo "验证成功<br />";
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}else {
		    //验证失败
		    //如要调试，请看alipay_notify.php页面的verifyReturn函数
		    echo "验证失败";
		}
    }
    //支付后返回的参数判断
    function myorder($ordtype){
        $ordtype=$_REQUEST['ordtype'];
        switch ($ordtype) {
            case 'payed':
                echo'已支付';
                break;
            
            case 'unpay':
                echo'未支付';
                break;
        }
    }
     //在线交易订单支付处理函数
     //函数功能：根据支付接口传回的数据判断该订单是否已经支付成功；
     //返回值：如果订单已经成功支付，返回true，否则返回false；
     function checkorderstatus($ordid){
        $Ord=M('Orderlist');
        $ordstatus=$Ord->where('ordid='.$ordid)->getField('ordstatus');
        if($ordstatus==1){
            return true;
        }else{
            return false;
        }
     }
     //处理订单函数
     //更新订单状态，写入订单支付后返回的数据
     function orderhandle($parameter){
        $ordid=$parameter['out_trade_no'];
        $data['payment_trade_no']      =$parameter['trade_no'];
        $data['payment_trade_status']  =$parameter['trade_status'];
        $data['payment_notify_id']     =$parameter['notify_id'];
        $data['payment_notify_time']   =$parameter['notify_time'];
        $data['payment_buyer_email']   =$parameter['buyer_email'];
        $data['ordstatus']             =1;
        $Ord=M('Orderlist');
        $Ord->where('ordid='.$ordid)->save($data);
     }

}