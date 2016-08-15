<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($list["Title"]); ?>— 实创装饰集团<?php echo ($list["CityName"]); ?>公司</title>
<meta name="viewport" id="viewportid" content="target-densitydpi=320,width=640,user-scalable=yes">
<script>
if (window.innerWidth > 640){
	document.getElementById('viewportid').setAttribute('content', 'target-densitydpi=285,width=640,user-scalable=yes');
}
</script>

<meta name="apple-touch-fullscreen" content="no" />
<meta content="telephone=no" name="format-detection" />
<meta name="apple-mobile-web-app-capable" content="no" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="">
<!--IOS 添加到主屏幕 应用图标-->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="">
<link rel="apple-touch-icon-precomposed" href="">
<link rel="shortcut icon" href="">
<link rel="stylesheet" media="all" type="text/css" href="/m/Public/Home/css/style.css" />
<script type="text/javascript" src="/m/Public/Home/js/jquery-1.9.1.min.js"></script>

<script type="text/javascript">
<!--

//-->
function tijiaos(){
		var form = $("#form");
		var UserName = $('#form input[name="UserName"]').val();
		var TelePhone = $('#form input[name="TelePhone"]').val();
		//alert(UserName);
		if(UserName == ''){
			alert('请填写姓名!');
		}else if (TelePhone == ''){
			alert('请填写电话!');
		}else{
			form.submit();
		}
}
</script>

	<script type="text/javascript">
		var url = "/m/index.php/Hd/ajaxgetbmnumber";
		var id = "<?php echo ($id); ?>";
		$.get(url,
			{id:id},
			function(txt){
					//alert(txt);
					$("#bm_numz").html('');
					$("#bm_numz").html(txt);
				}
			
			);
	</script>
</head>
<body>
<div class="contain">
	<div class="header">
    	<h1><?php echo ($list["Title"]); ?></h1>
        <div class="dh_01"><a href="tel://<?php echo ($cityinfo["TELEPHONE"]); ?>"><?php echo ($cityinfo["TELEPHONE"]); ?></a></div>
        <img src="http://www.sc.cc/<?php echo ($list["background"]); ?>" width="560" height="262" />
    </div>
    <div class="main">
    
    	<div class="main_bm">
        	本场活动已报名<p class="bm_num" id="bm_numz"></p>人
            <div class="baoming">
<form id="form"  action="/m/index.php/Hd/bm/" method="post" name="form" >
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  
            <input type="hidden" name="ClassRoomID" value="<?php echo ($id); ?>">
            <input type="hidden" name="ClassRoomName" value="<?php echo ($list['Title']); ?>">
            <input type="hidden" name="qudao" value="<?php echo ($utm_source); ?>">
            <input type="hidden" value="add" name="action">
            <input type="hidden" value="<?php echo ($Promoterid); ?>" name="Promoterid">
            <input type="hidden" value="<?php echo ($PromoterName); ?>" name="PromoterName">
            <input type="hidden" value="<?php echo ($utm_term); ?>" name="utm_term">
            <input name="CityID" type="hidden" id="CityID" value="<?php echo ($list['CityID']); ?>">
            <input name="CityName" type="hidden" id="CityName" value="<?php echo ($list['CityID']); ?>">
            <input name="renshu" type="hidden" id="renshu" value="1">
  <tr>
    <td>姓名:&nbsp;</td>
    <td><input name="UserName" type="text" class="input input2"/></td>
     <td>电话:&nbsp;</td>
    <td ><input type="text" class="input" name="TelePhone" size="5" maxlength="11" /></td>
    <td ><a href="javascript:tijiaos();"><input type="button" value="免费报名" class="mf"></a></td>
  </tr>
</table>
</form>
			</div>
        </div>
        
        <div class="ld">
        	<p class="x_bt">活动亮点</p>
            <div class="ld_con">
            <pre style="font-family: 微软雅黑;font-size:18px;">
<?php echo ($list["hdld"]); ?>
</pre>
</div>
        
        <div class="ld">
        	<p class="x_bt">合作品牌<span>&nbsp;&nbsp;&nbsp;&nbsp;进口主材 欧洲标准 绿色环保</span></p>
            <div class="pp_con">
            	<span>瑞士卢森地板</span><span>丹麦福乐阁墙漆</span><span>德国高仪卫浴</span><span class="mn">西班牙MAPISA瓷砖</span><span>德国爱格地板</span><span>巴赫曼德式木门</span><span>德国唯宝卫浴</span><span class="mn">西班牙TITAN堤丹漆</span>
                <div class="clear"></div>
            </div>
        </div>
        
        <div class="ld ld2">
        	<p class="x_bt">品牌优势</p>
            <div class="ys_con">
            	实创装饰标准化整体家居服务领导者，整体家装模式缔造者与领航者。凭借17年品牌经验，全面整合进口主材、标准施工和一站式售后服务，使实创成为迄今为止，项目最全、性价比最高、报价最透明的整体家装装修公司，亦是业内唯一《福布斯》上榜100强的品牌家装企业！

            </div>
            <p class="ys_c2"><img src="/m/Public/Home/images/index_11.jpg" width="560" height="150" /></p>
            <p class="ys_c3">一站式整体家居服务设计/选材/施工/售 后一站搞定 省钱更省心</p>
            <p class="ys_c3 ys_c4">全责售后避免推诿更省心</p>
            <p class="ys_c3 ys_c5">原装正品  假一赔三</p><p class="ys_c3 ys_c5 ys_c6">环保不达标  负全责</p>
            <div class="clear"></div>
        </div>
        
        <div class="ld">
        	<p class="x_bt x_bt2">活动流程</p>
            <img src="/m/Public/Home/images/index_06.jpg" width="560" height="250" />
        </div>
        
    </div>
	<div class="footer">
    	<div class="foot"><span>整体家装</span><span>装修日记</span><span>设计案例</span><span>标准工程</span></div>
        <div class="phone"><p class="p_left">官方微信：<span class="wx">sitrust288</span></p><p class="p_right">免费咨询热线：<span class="dh"><a href="tel://<?php echo ($cityinfo["TELEPHONE"]); ?>"><?php echo ($cityinfo["TELEPHONE"]); ?></a></span></p></div>
    </div>


</div>
</div>


</body>
</html>