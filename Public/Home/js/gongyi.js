$(function(){
	/*gongyi1*/
		var slider = Swipe(document.getElementById('gongyi_img1'), {
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
		
		/*gongyi2*/
		var slider = Swipe(document.getElementById('gongyi_img2'), {
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
		/*gongyi3*/
		var slider = Swipe(document.getElementById('gongyi_img3'), {
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
		/*gongyi4*/
		var slider = Swipe(document.getElementById('gongyi_img4'), {
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
		/*gongyi5*/
		var slider = Swipe(document.getElementById('gongyi_img5'), {
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
		/*gongyi6*/
		var slider = Swipe(document.getElementById('gongyi_img6'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets6.length;
			while (i--) {
				bullets6[i].className = ' ';
			}
			bullets6[pos].className = 'on';
		}
		});
		var bullets6 = document.getElementById('scroll_position6').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position6').width()
			});
		});
		/*gongyi7*/
		var slider = Swipe(document.getElementById('gongyi_img7'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets7.length;
			while (i--) {
				bullets7[i].className = ' ';
			}
			bullets7[pos].className = 'on';
		}
		});
		var bullets7 = document.getElementById('scroll_position7').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position7').width()
			});
		});
		/*gongyi8*/
		var slider = Swipe(document.getElementById('gongyi_img8'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets8.length;
			while (i--) {
				bullets8[i].className = ' ';
			}
			bullets8[pos].className = 'on';
		}
		});
		var bullets = document.getElementById('scroll_position8').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position8').width()
			});
		});
		/*gongyi9*/
		var slider = Swipe(document.getElementById('gongyi_img9'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets9.length;
			while (i--) {
				bullets9[i].className = ' ';
			}
			bullets9[pos].className = 'on';
		}
		});
		var bullets9 = document.getElementById('scroll_position9').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position9').width()
			});
		});
		/*gongyi10*/
		var slider = Swipe(document.getElementById('gongyi_img10'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets10.length;
			while (i--) {
				bullets10[i].className = ' ';
			}
			bullets10[pos].className = 'on';
		}
		});
		var bullets10 = document.getElementById('scroll_position10').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position10').width()
			});
		});
		/*gongyi11*/
		var slider = Swipe(document.getElementById('gongyi_img11'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets11.length;
			while (i--) {
				bullets11[i].className = ' ';
			}
			bullets11[pos].className = 'on';
		}
		});
		var bullets11 = document.getElementById('scroll_position11').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position11').width()
			});
		});
		/*gongyi12*/
		var slider = Swipe(document.getElementById('gongyi_img12'), {
		auto: 3000,
		continuous: true,
		callback: function(pos) {
			var i = bullets12.length;
			while (i--) {
				bullets12[i].className = ' ';
			}
			bullets12[pos].className = 'on';
		}
		});
		var bullets12 = document.getElementById('scroll_position12').getElementsByTagName('li');
		$(function(){
			$('.scroll_position_bg').css({
				width:$('#scroll_position12').width()
			});
		});
})