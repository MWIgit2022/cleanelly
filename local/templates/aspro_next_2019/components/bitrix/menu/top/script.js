$(document).ready(function(){
$('.menu-item').hover(
		function(){
			$(this).find('img').first().hide();
			$(this).find('img').last().show();
		},
		function(){
			$(this).find('img').last().hide();
			$(this).find('img').first().show();
		}
	);
});