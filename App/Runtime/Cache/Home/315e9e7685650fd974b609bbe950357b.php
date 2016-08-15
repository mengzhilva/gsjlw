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
<link href="/gsjlwm/Public/Home/css/reset.css" type="text/css" rel="stylesheet">
<link href="/gsjlwm/Public/Home/css/ui-base.css" type="text/css" rel="stylesheet">
<script src="/gsjlwm/Public/Home/js/jquery-1.9.1.min.js" type="text/javascript"></script>
<script src="/gsjlwm/Public/Home/js/Component.js" type="text/javascript"><!--全局公用效果js--></script>
<script type="text/javascript" src="/gsjlwm/Public/Home/js/hhSwipe.js"><!--手机幻灯滑屏js文件--></script>
<script type="text/javascript" name="baidu-tc-cerfication" data-appid="4342584" src="http://apps.bdimg.com/cloudaapi/lightapp.js"></script>
<!--Gridsum tracking code begin. -->
<script type='text/javascript'>

</script>
<!--Gridsum tracking code end. -->




<!--页面独立js和css区-->
<link href="/gsjlwm/Public/Home/css/yangbanjian.css" type="text/css" rel="stylesheet"><!--当前页面独立css文件-->
<script src="/gsjlwm/Public/Home/js/taocan.js" type="text/javascript"><!--当前页面独立js文件--></script>
</head>
<body>
<div class="cs-view-wrap clearfix">
  <header class="sc-header-search top-fixed">
    <!--<div class="header-search-row">
     <input type="text" class="top-search-inut" placeholder="输入关键字">
     <a href="#" class="search-btn">搜索</a>
    </div> -->
    <section class="sc-logo-row clearfix">
      <h1 class="sc-logo"><a href="<?php echo U('/index/index?city='.$city);?>"><img src="/gsjlwm/Public/Home/images/logo.png"></a></h1>
     <div class="header-search-row">
     <input type="text" id="search" class="top-search-inut" placeholder="输入关键字">
    </div>
    <a class="searcha" href="javascript:search();"><img class="search" src="/Public/Home/css/images/search-icon.png" /></a>
      <a class="caidan" href="<?php echo U('/ChooseCity/index?city='.$city);?>">
      <div class="sc-top-city">
        <i></i>
      </div>
      </a>
      <a class="caidangr" href="<?php echo U('/ChooseCity/index?city='.$city);?>">
      <div class="sc-top-gr">
        <i></i>
      </div>
      </a>
    </section>
    <!--logo区部分 end-->
  </header>
  <!--代码统计BEDIN-->
  <script type='text/javascript'>
  
  function search(){
	  var se = $("#search").val();
	  var url = "<?php echo U('/book/search?key='"+se+");?>";
	  alert(url);
	  window.location.href=url;
	  
  }
</script>
<!--代码统计END-->

  <!--header end-->
  <!--主体内容 start-->
  <div class="sc-view-container">
    <section class="sc-floor-row section-1 clearfix">
        <a href="<?php echo U('/index/index');?>" class="last"></a>
        <span class="jz-top-icon"></span>
        <h3 class="clearfix">
            <p class="chi-ti detailtitle"><?php echo ($book["name"]); ?>-<?php echo ($info["title"]); ?></p>
        </h3>
    </section>
    <section class="clearfix book">
        <div class="index-classify bookdetail">
        	<?php echo ($info["content"]); ?>
        </div>
    </section>

  </div>
  <!--主体内容 end-->

<!--底部悬浮
-->
<div class="zoom-bottom-fiexd">
  <h2 class="z-b-f-l"><a href="<?php echo U('/book/lists?id='.$book['id'].'');?>" class="free-lf">目录</a></h2>
  <?php if($pre != ''): ?><p class="z-b-f-r"><a href="<?php echo U('/book/detail?id='.$pre.'');?>" class="free-lf">上一章</a></p><?php endif; ?>
  <?php if($next != ''): ?><p class="z-b-f-r"><a href="<?php echo U('/book/detail?id='.$next.'');?>" class="free-lf">下一章</a></p><?php endif; ?>
</div>
<!--底部悬浮 end-->


</div>
</body>
</html>