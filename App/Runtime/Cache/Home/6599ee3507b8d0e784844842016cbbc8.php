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
<link href="/gsjlwm/Public/Home/css/yangbanjian.css" type="text/css" rel="stylesheet"><!--当前页面独立css文件-->
<link href="/gsjlwm/Public/Home/css/lanrentuku.css" type="text/css" rel="stylesheet"><!--当前页面独立css文件-->
<script src="/gsjlwm/Public/Home/js/lanrentuku.js" type="text/javascript"><!--当前页面独立js文件--></script>
<script src="/gsjlwm/Public/Home/js/jquery-1.4.2.min.js" type="text/javascript"><!--当前页面独立js文件--></script>
<script src="/gsjlwm/Public/Home/js/lanrentuku.js" type="text/javascript"><!--当前页面独立js文件--></script>
<script src="/gsjlwm/Public/Home/js/iepng.js" type="text/javascript"><!--当前页面独立js文件--></script>
<script type="text/javascript">
   EvPNG.fix('div ');
</script>
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
    <section class="sc-floor-row section-1 clearfix">
        <a href="<?php echo U('/index/index');?>" class="last"></a>
        <span class="jz-top-icon"></span>
        <h3 class="clearfix">
            <p class="chi-ti detailtitle"><?php echo ($info["title"]); ?></p>
        </h3>
    </section>
    <section class="clearfix book">
        <div class="index-classify bookdetail">
        	<?php echo ($info["content"]); ?>
        </div>
    </section>
    <section class="clearfix book">
        <h3 class="simptitle">接龙内容</h3>
<div id="imgPlay">
<ul class="imgs" id="actor" style=" margin-left: 0px;">
                  <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li> 
                           <div class="xsname"><a href="javascript:addson(<?php echo ($vo['sid']); ?>);"><span class="gstitle"><?php echo ($vo["title"]); ?> </span></a></div>
                           
                          <div class="xscontent"><a href="javascript:addson(<?php echo ($vo['sid']); ?>);"><?php echo ($vo["content"]); ?>...</a></div>
					
					</li><?php endforeach; endif; else: echo "" ;endif; ?>
</ul>
<div class="num" >
<p class="lc"></p>
<p class="mc" style="width: 72px;"></p>
<p class="rc"></p></div>
<div class="num" id="numInner" ><span class="on"></span><span class=""></span><span class=""></span><span class=""></span></div>
<div class="prev">上一张</div>
<div class="next">下一张</div>
</div>
        <a href="" class="simptitlemore">查看更多</a>
    </section>

  </div>
  <!--主体内容 end-->

<!--底部悬浮
-->
<div class="zoom-bottom-fiexd">
  <h2 class="z-b-f-l"><a href="<?php echo U('/book/lists?id='.$book['id'].'');?>" class="free-lf">目录</a></h2>
  <?php if($pre != ''): ?><p class="z-b-f-r"><a href="<?php echo U('/book/detail?id='.$pre.'');?>" class="free-lf">上一章</a></p><?php endif; ?>
  <?php if($next != ''): ?><p class="z-b-f-r"><a href="<?php echo U('/book/detail?id='.$next.'');?>" class="free-lf next">下一章</a></p><?php endif; ?>
</div>
<!--底部悬浮 end-->


</div>
<script>
function addson(sid){ 
	
	var wids = $(".book").width();
	$('#actor').html('<li style="width:'+wids+'px">asdfasdfasdf</li><li style="width:'+wids+'px">asdfasdfasdf</li><li style="width:'+wids+'px">asdfasdfasdf</li>');
	lbt();
} 
function lbt(){
var t = false;
var str = '';
var speed = 500;
var wid = $(".book").width();
var w = wid;
var n = $('#actor li').length;
var numWidth = n * 18;
var _left = (w - (numWidth + 26)) / 2;
var c = 0;
$('#actor').width(w * n);
$('#actor li').width(w);
$('#actor li').each(function(i) {
    str += '<span></span>'
});
$('#numInner').width(numWidth).html(str);
$('#imgPlay .mc').width(numWidth);
$('#imgPlay .num').css('left', _left);
$('#numInner').css('left', _left + 13);
$('#numInner span:first').addClass('on');
function cur(ele, currentClass) {
    ele = $(ele) ? $(ele) : ele;
    ele.addClass(currentClass).siblings().removeClass(currentClass)
}
$('#imgPlay .next').click(function() {
    slide(1)
});
$('#imgPlay .prev').click(function() {
    slide( - 1)
});
function slide(j) {
	var n = $('#actor li').length;
    if ($('#actor').is(':animated') == false) {
        c += j;
        if (c != -1 && c != n) {
            $('#actor').animate({
                'marginLeft': -c * w + 'px'
            },
            speed)
        } else if (c == -1) {
            c = n - 1;
            $("#actor").css({
                "marginLeft": -(w * (c - 1)) + "px"
            });
            $("#actor").animate({
                "marginLeft": -(w * c) + "px"
            },
            speed)
        } else if (c == n) {
            c = 0;
            $("#actor").css({
                "marginLeft": -w + "px"
            });
            $("#actor").animate({
                "marginLeft": 0 + "px"
            },
            speed)
        }
        cur($('#numInner span').eq(c), 'on')
    }
}
$('#numInner span').click(function() {
    c = $(this).index();
    fade(c);
    cur($('#numInner span').eq(c), 'on')
});
function fade(i) {
    if ($('#actor').css('marginLeft') != -i * w + 'px') {
        $('#actor').css('marginLeft', -i * w + 'px');
        $('#actor').fadeOut(0,
        function() {
            $('#actor').fadeIn(500)
        })
    }
}
function start() {
    t = setInterval(function() {
        slide(1)
    },
    5000)
}
function stopt() {
    if (t) clearInterval(t)
}
$("#imgPlay").hover(function() {
    stopt()
},
function() {
    start()
});
start()
}
lbt();
</script>
</body>
</html>