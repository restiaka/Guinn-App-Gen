$(document).ready(function() {
	
$('.big-img').hover(function(){
	
	$('.img-nav a').stop(true,false).animate({opacity: .65},200)},
	
	function(){$('.img-nav a').stop(true,false).animate({opacity: 0},200)
	});

$('.img-nav a').hover(function(){
	$(this).stop(true,false).animate({opacity: .85},100)},
	function(){$(this).stop(true,false).animate({opacity: 0.65},50)
});
	
});