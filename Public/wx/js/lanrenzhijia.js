// 代码版权所有：懒人之家 www.lanrenzhijia.com  转载请注明出处
$(function(){
	var urls = $("#downimgurl").html();
	//将内容插入到contain开始处，页面加载完毕后自动展开
	$('.contain').prepend("<div class='cont-lan'><div class='lanrenzhijia'><a href='"+urls+"' target='_blank' class='link'><img src='/wx/images/dp-17.jpg' width='100%' height='100%' /></a></div></div>");
	$('.lanrenzhijia').slideDown(800,function(){
		//$('.lanrenzhijia').append("<a href='javascript:;' class='up'></a>");
		//$(".cont-lan").css({"display":"none","height":200});								  
	});	
	//设置延时函数
	function adsUp(){
		$('.lanrenzhijia').animate({
			height:'0px'						 
		},1000,function(){
			$('.xl-tb').find('.up').addClass('down').removeClass('up');	
			$('.cont-lan').css('height',0);	
		});	
	}
	//两秒钟后自动收起
	var t = setTimeout(adsUp,2000);
	//点击收起
	$('.cont-lan').live('click',function(){
		clearTimeout(t);
		$('.lanrenzhijia').animate({
			height:'0px'						 
		},function(){
			$('.xl-tb').find('.up').addClass('down').removeClass('up');	
			$('.cont-lan').css('height',0);
		});	 
	});	
	
	//点击下拉
	$('.lanrenzhijia a.down,.xl-tb a.down').live('click',function(){
		//$(this).css({
//			opacity:'0'	,
//			filter:'alpha(opacity=0)'
//		});
		$('.cont-lan').css('height','100%');
		$('.lanrenzhijia').animate({
			height:'130px'
		},function(){
			$('.xl-tb').find('.down').addClass('up').removeClass('down').css({opacity:'1',filter:'alpha(opacity=100)'});
			
		});	 
	});
	
	

});