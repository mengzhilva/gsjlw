$(function(){
	/*space1*/
		var slider = Swipe(document.getElementById('space_img1'), {
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
	/*space2*/
		var slider = Swipe(document.getElementById('space_img2'), {
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
	/*space3*/
		var slider = Swipe(document.getElementById('space_img3'), {
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
		var bullets1 = document.getElementById('scroll_position3').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position3').width()
			});
		});	
	/*space3*/
		var slider = Swipe(document.getElementById('space_img4'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets4.length;
			while (i--) {
				bullets4[i].className = ' ';
			}
			bullets4[pos].className = 'on';
		}
		});
		var bullets4 = document.getElementById('scroll_position4').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position4').width()
			});
		});	
	/*space5*/
		var slider = Swipe(document.getElementById('space_img5'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets5.length;
			while (i--) {
				bullets5[i].className = ' ';
			}
			bullets5[pos].className = 'on';
		}
		});
		var bullets5 = document.getElementById('scroll_position5').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position5').width()
			});
		});				
})