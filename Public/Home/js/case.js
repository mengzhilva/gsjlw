$(function(){
	var NdownBtn=$('.case-nav .fg-down');
	    NdownList=$('.pdown'),
		NList1=$('.case-nav .list1'),
		NList2=$('.case-nav .list2'),
		NList3=$('.case-nav .list3'),
		NList4=$('.case-nav .list4');
		
	var ContainerW=$('.sc-view-container').width();
	//NList1.find('.pdown').css({width:ContainerW,left:0});
	NList1.find('.pdown').css({width:NList2.width()});
	NList2.find('.pdown').css({width:NList2.width()});
	NList3.find('.pdown').css({width:NList3.width()});
	NList4.find('.pdown').css({width:NList3.width()});




	var toggleSate=true;
	NdownBtn.on('click',function(event){
		event.stopPropagation(); //  阻止事件冒泡
		NdownList.slideUp();
		if(toggleSate){
			$(this).parent().find('.pdown').slideDown();
			toggleSate=false;
		}else{
			$(this).parent().find('.pdown').slideUp();
			toggleSate=true;
		}
	});
	
	$('.pdown').find('li').click(function(event){
		event.stopPropagation(); //  阻止事件冒泡
		//$('.pdown').slideUp();
	});
	$(document).click(function(event){
		event.stopPropagation(); //  阻止事件冒泡
		NdownList.slideUp();
	});/**/
	
	/*scroll_img3*/
		var slider = Swipe(document.getElementById('case_img'), {
			auto: 0,
			continuous: true
			
		});

})