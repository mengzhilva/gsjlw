// JavaScript Document
$(function(){
	scCotanierC();//主体内容ui控制
});
//主体内容ui控制
function scCotanierC(){
	var scConTanier=$('.sc-view-container'),
	    scHeaderH=$('.sc-header-search');
	if(scHeaderH.hasClass('top-fixed')){
		 scConTanier.css({'margin-top':scHeaderH.innerHeight()});
	};
	
}


// 滚屏滑动
$(function(){
	try{
		var slider = Swipe(document.getElementById('scroll_img'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets.length;
			while (i--) {
				bullets[i].className = ' ';
			}
			bullets[pos].className = 'on';
		}
		});
		var bullets = document.getElementById('scroll_position').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position').width()
			});
		});
		
		/*scroll_img1*/
		var slider = Swipe(document.getElementById('scroll_img1'), {
			auto: 3000,
			continuous: true,
			callback: function(pos) {
				var i = bullets1.length;
				while (i--) {
					bullets1[i].className = ' ';
				}
				bullets1[pos].className = 'on';
			}
			
			
		});
		var bullets1 = document.getElementById('scroll_position1').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position1').width()
			});
		});
		/*scroll_img2*/
		var slider = Swipe(document.getElementById('scroll_img2'), {
			auto: 3000,
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
		/*scroll_img3*/
		var slider = Swipe(document.getElementById('scroll_img3'), {
			auto: 3000,
			continuous: true,
			callback: function(pos) {
				var i = bullets3.length;
				while (i--) {
					bullets3[i].className = ' ';
				}
				bullets3[pos].className = 'on';
			}
			
			
		});
		var bullets3 = document.getElementById('scroll_position3').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position3').width()
			});
		});
		
		
		
		
		
	}catch(err){
		console.log('err');
	} 

});