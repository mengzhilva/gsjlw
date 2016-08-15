$(function(){
	var NdownBtn=$('.list4 .fg-down')
	var toggleSate=true;
	NdownBtn.on('click',function(event){
		event.stopPropagation(); //  阻止事件冒泡
		if(toggleSate){
			$(this).parent().find('.pdown').animate({height:'+245px'},'fast');
			toggleSate=false;
		}else{
			$(this).parent().find('.pdown').animate({height:'-245px'},'fast');
			toggleSate=true;
		}
	});
	
	$('.pdown').find('li').click(function(event){
		event.stopPropagation(); //  阻止事件冒泡
		$('.pdown').animate({height:'-245px'},'fast');
	});
	$(document).click(function(event){
		event.stopPropagation(); //  阻止事件冒泡
		if($('.pdown')>100){
		   $('.pdown').animate({height:'-245px'},'fast');
		}else{
		   $('.pdown').animate({height:'0'},'fast');
		}
	});/**/
})