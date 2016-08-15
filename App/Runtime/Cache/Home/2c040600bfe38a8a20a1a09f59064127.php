<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ($title); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="author" content="m.sc.com">
<!--公共js和css区-->
<link href="/m/Public/Home/css/reset.css" type="text/css" rel="stylesheet">
<link href="/m/Public/Home/css/ui-base.css" type="text/css" rel="stylesheet">
<script src="/m/Public/Home/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="/m/Public/Home/js/Component.js" type="text/javascript"><!--全局公用效果js--></script>
<script type="text/javascript" src="/m/Public/Home/js/hhSwipe.js"><!--手机幻灯滑屏js文件--></script>
<script type="text/javascript" name="baidu-tc-cerfication" data-appid="4342584" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>
<!--Gridsum tracking code begin. -->
<script type='text/javascript'>
	(function(){
		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.async = true;
		s.src = (location.protocol == 'https:' ? 'https://ssl.' : 'http://static.') + 'gridsumdissector.com/js/Clients/GWD-002542-2331F6/gs.js';
		var firstScript = document.getElementsByTagName('script')[0];
		firstScript.parentNode.insertBefore(s, firstScript);
	})();
</script>
<!--Gridsum tracking code end. -->





<!--页面独立js和css区-->
<link href="/m/Public/Home/css/case.css" type="text/css" rel="stylesheet"><!--当前页面独立css文件-->
<link href="/m/Public/Home/css/share.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="/m/Public/Home/js/case.js"></script>
</head>
<body>
<div class="cs-view-wrap clearfix">
  <header class="sc-header-search top-fixed">
<!--     <div class="header-search-row">
     <input type="text" class="top-search-inut" placeholder="输入您要搜索的产品">
     <a href="#" class="search-btn">搜索</a>
    </div> -->
    <section class="sc-logo-row clearfix">
      <h1 class="sc-logo"><a href="<?php echo U('/index/index?city='.$city);?>"><img src="/m/Public/Home/images/logo.png"></a></h1>
      <a href="<?php echo U('/ChooseCity/index?city='.$city);?>">
      <div class="sc-top-city">
        <i></i>
        <strong class="sc-top-city-choose"><?php echo ($cityinfo["NAME"]); ?></strong>
      </div>
      </a>
    </section>
    <!--logo区部分 end-->
  </header>
  <!--header end-->
  <!--主体内容 start-->
  <div class="sc-view-container">
    <section class="sc-floor-row section-1 clearfix">
    	<a href="<?php echo U('/index/index?city='.$city);?>" class="last"></a>
    	<span class="jz-top-icon jz-top-icon-s"></span>
        <h3 class="clearfix case-h3">
        	<p class="chi-ti">实创服务</p>
            <!--<p class="eng-ti">CASE</p>-->
        </h3>
        <div class="top-share-cl" style="width:9em;">
         <a href="#" class="top-share-btn"><strong class="s-font share-wenzi"></strong><!--<i class="top-share-icon"></i>--></a>
         <div class="fx">
          <div class="bdsharebuttonbox"><a href="#" data-cmd="more"></a><!--<a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>--></div>

</div>
         </div>
    </section>
    <section class="sc-floor-row designer-1 clearfix">
		<img src="/m/Public/Home/images/ser-01.jpg"/><img src="/m/Public/Home/images/ser-02.jpg"/><img src="/m/Public/Home/images/ser-03.jpg"/><img src="/m/Public/Home/images/ser-04.jpg"/><img src="/m/Public/Home/images/ser-05.jpg"/><img src="/m/Public/Home/images/ser-06.jpg"/><img src="/m/Public/Home/images/ser-07.jpg"/><img src="/m/Public/Home/images/ser-08.jpg"/><img src="/m/Public/Home/images/ser-09.jpg"/><img src="/m/Public/Home/images/ser-10.jpg"/><img src="/m/Public/Home/images/ser-11.jpg"/><a href="http://j.looyu.com/WebModule/chat/p.do?c=<?php echo ($cityinfo['LEYUCID']); ?>&f=<?php echo ($cityinfo['LEYUFID']); ?>&g=<?php echo ($cityinfo['LEYUGID']); ?>" target="_blank"><img src="/m/Public/Home/images/ser-12.jpg"/></a><img src="/m/Public/Home/images/ser-13.jpg"/> 
    </section>
  </div>
  <!--主体内容 end-->
<!--footer-->
<!-- <footer class="sc-view-footer clearfix">页脚内容正在设计中....</footer> -->
<!--footer end-->
</div>
</body>
</html>