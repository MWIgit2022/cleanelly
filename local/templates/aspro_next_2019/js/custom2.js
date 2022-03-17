window.performance.mark('mark_fully_loaded');
$(document).ready(function()
{
    $(window).scroll(function()
    {
        if ($('.lazyLoadDiv:not(.loaded)').length > 0 && $(window).scrollTop() > 0)
        {
            $('.lazyLoadDiv').addClass('loaded');
            $.get('/ajax/main-lazy.php', {}, function(out)
            {
                $('.lazyLoadDiv').html(out);
                initAllElements();
            });
        }
    });
	
});

BX.ready(function(){
	if($('.item_block.banner').length>0){
		$('.item_block.banner').each(function(i,v){
			$(this).css('height', $(this).prev().height()+59+'px');
		});
	}
})

BX.addCustomEvent('onAjaxSuccess', function(){
     if($('.item_block.banner').length>0){
		$('.item_block.banner').each(function(i,v){
			$(this).css('height', $(this).prev().height()+59+'px');
		});
	}
});