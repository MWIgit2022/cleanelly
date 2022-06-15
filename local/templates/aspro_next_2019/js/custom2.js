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

$(document).on('click', '.confirm_region .btn', function(){
	$('#title-search-input_fixed').css('padding-left','0');
})

function autoHeightFlexSlider(clas){
	var height = $(clas).find('li').height();
	$(clas).css('height',height);
}

function elementInViewport(el){
    var bounds = el.getBoundingClientRect();
    return (
        (bounds.top + bounds.height > 30) && // Елемент ниже верхней границы
        (window.innerHeight - bounds.top > 30) && // Выше нижней
        (bounds.left + bounds.width > 30) && // Правее левой
        (window.innerWidth - bounds.left > 30)// Левее правой
    );
}


$(document).ready(function(){
	setInterval(function(){
		$('.review_c').find('li').each(function(){
			var inViewPort = elementInViewport(this);
		
			if(inViewPort){
				$('.review_c').css('height', $(this).height()+50);
			}
		});
	}, 100);
})