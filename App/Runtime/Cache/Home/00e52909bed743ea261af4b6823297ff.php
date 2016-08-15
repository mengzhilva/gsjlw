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
<!--Gridsum tracking code begin. -->
<script type='text/javascript'>

</script>
<!--Gridsum tracking code end. -->




<!--页面独立js和css区-->
<link href="/gsjlwm/Public/Home/css/cs-index2015.css" type="text/css" rel="stylesheet"><!--当前页面独立css文件-->
<script src="/gsjlwm/Public/Home/js/sc-index2015.js" type="text/javascript"><!--当前页面独立js文件--></script>
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
     <input type="text" id="search" class="top-search-inut" value="<?php echo ($key); ?>" placeholder="输入关键字">
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
	  var url = "<?php echo U('/book/search');?>"+'?key='+se;
	  alert(url);
	  window.location.href=url;
	  
  }
</script>
<!--代码统计END-->

  <!--header end-->
  <!--主体内容 start-->
  <div class="sc-view-container">
    <section class="sc-floor-row clearfix">
      <div class="ui-banner-adv index-banner-wrap index-slide">
        <!--scroll-->
          <div class="scroll relative">
            <div class="scroll_box" id="scroll_img">
            </div>
          <span class="scroll_position_bg opacity6"></span>
              <ul class="scroll_position" id='scroll_position'>              
              </ul>
          </div>
	  <!--scroll-->
       
      </div>
    </section>
    <!--end-->
    <section class="sc-floor-row clearfix">     
    <div class="i-fg-line"><span class="ifg-icon02"></span><h2 class="i-fg-title"><strong>故事接龙</strong></h2></div>
    
      <ul class="index-classify clearfix">
                  <?php if(is_array($gsjl)): $i = 0; $__LIST__ = $gsjl;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                           <a href="<?php echo U('/story/detail?id='.$vo['sid'].'');?>"><span class="gstitle"><?php echo ($vo["title"]); ?> </span></a>
                           
                           <div class="gscontent"><a href="<?php echo U('/story/detail?id='.$vo['sid'].'');?>"><?php echo (msubstr($vo["content"],0,60,'utf-8',false)); ?>...</a></div>
					
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </section>
    <!--**********end**********-->
    <section class="sc-floor-row clearfix">
    <div class="i-fg-line"><span class="ifg-icon01"></span><h2 class="i-fg-title"><strong>推荐小说</strong></h2></div>
    
      <ul class="index-classify clearfix">
                  <?php if(is_array($xstj)): $i = 0; $__LIST__ = $xstj;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li> <div class="xsimg"><img src="<?php echo ($imgurl); echo ($vo["img"]); ?>" /></div>
                           <div class="xsname"><a href="<?php echo U('/book/index?id='.$vo['id'].'');?>"><span class="gstitle"><?php echo ($vo["name"]); ?> </span></a></div>
                           
                          <div class="xscontent"><a href="<?php echo U('/book/index?id='.$vo['id'].'');?>"><?php echo ($vo["description"]); ?>...</a></div>
					
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
     
    </section>
    <!--**********end**********-->
    
    <section class="sc-floor-row clearfix ">
     <div class="i-fg-line"><span class="ifg-icon02"></span><h2 class="i-fg-title"><strong>最新小说</strong></h2></div>
         <ul class="index-classify clearfix">
                  <?php if(is_array($xszx)): $i = 0; $__LIST__ = $xszx;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voz): $mod = ($i % 2 );++$i;?><li> <div class="xsimg"><img src="<?php echo ($imgurl); echo ($voz["img"]); ?>" /></div>
                           <div class="xsname"><a href="<?php echo U('/book/index?id='.$voz['id'].'');?>"><span class="gstitle"><?php echo ($voz["name"]); ?> </span></a></div>
                           
                          <div class="xscontent"><a href="<?php echo U('/book/index?id='.$voz['id'].'');?>"><?php echo ($voz["description"]); ?>...</a></div>
					
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
     
    </section>
    <!--**********end**********-->
    
    <section class="sc-floor-row clearfix ">
     <div class="i-fg-line"><span class="ifg-icon02"></span><h2 class="i-fg-title"><strong>热门小说</strong></h2></div>
         <ul class="index-classify clearfix">
                  <?php if(is_array($xsrd)): $i = 0; $__LIST__ = $xsrd;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voz): $mod = ($i % 2 );++$i;?><li> <div class="xsimg"><img src="<?php echo ($imgurl); echo ($voz["img"]); ?>" /></div>
                           <div class="xsname"><a href="<?php echo U('/book/index?id='.$voz['id'].'');?>"><span class="gstitle"><?php echo ($voz["name"]); ?> </span></a></div>
                           
                          <div class="xscontent"><a href="<?php echo U('/book/index?id='.$voz['id'].'');?>"><?php echo ($voz["description"]); ?>...</a></div>
					
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
     
    </section>
  <!--主体内容 end-->

<!--footer-->
<footer class="sc-view-footer clearfix">    
</footer>
<!--footer end-->
<!--悬浮操作
  <div class="i-xf-icon-wrap">
    <ul class="i-xf-list clearfix">
      <li><a href="javascript:alert('敬请期待……');" class="i-xf-icon01">&nbsp;</a></li>
      <li><a href="http://j.looyu.com/WebModule/chat/p.do?c=<?php echo ($cityinfo["LEYUCID"]); ?>&f=<?php echo ($cityinfo["LEYUFID"]); ?>&<?php echo ($leyurl); ?>" class="i-xf-icon02" target="_blank">&nbsp;</a></li>
      <li><a href="tel:<?php if($Promoter["PromoterTel"] != ''): echo ($Promoter["PromoterTel"]); else: echo ($cityinfo["TELEPHONE"]); endif; ?>" class="i-xf-icon03" target="_blank">&nbsp;</a></li>
      <li><a href="<?php echo U('/order/index?type=12&city='.$city.'');?>" class="i-xf-icon04">&nbsp;</a></li>
    </ul>
  </div>-->
  <!--悬浮操作 end-->  
</div>
</body>
</html>