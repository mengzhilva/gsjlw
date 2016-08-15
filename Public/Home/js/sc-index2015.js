// 只有首页才用到的js
$(function(){
	//给您推荐
	var TjianH=$('.i-sc-tuijian-row').innerHeight();
	$('.tuijian-h3').height(TjianH);
	$('.tuijian-ul').height(TjianH);
	
	
	
	
	///*轮播2*/
		var slider = Swipe(document.getElementById('scroll_img2'), {
		auto: 4000,
		continuous: true,
		callback: function(pos) {
			var i = bullets2.length;
			while (i--) {
				bullets2[i].className = ' ';
			}
			bullets2[pos].className = 'on';
		}
		});
		var bullets2 = document.getElementById('scroll_position2').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position2').width()
			});
		});
});