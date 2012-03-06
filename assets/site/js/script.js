$(document).ready(function() {		
	
$('.gallery-list li').hover(function(){
	$(this).find('.see-more').stop(true,false).animate({opacity: 1},100)}, function(){
		$(this).find('.see-more').stop(true,false).animate({opacity: 0},66)
	});
	
	$('.hide').click(function(){
		
		});
});
	
