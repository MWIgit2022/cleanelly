 var lazyloadThrottleTimeout;
    function lazyload () {
        var lazyloadImages = document.querySelectorAll("img.lazy");
        if(lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }
        lazyloadThrottleTimeout = setTimeout(function() {
            var scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if(img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                }
            });
            if(lazyloadImages.length == 0) {
                document.removeEventListener("scroll", lazyload);
                window.removeEventListener("resize", lazyload);
                window.removeEventListener("orientationChange", lazyload);
            }
        }, 20);
    }

$(document).ready(function(){
	if($('.overl_action_banner').length>0){
		setTimeout(function(){
			$('.overl_action_banner').css('display','flex');
			BX.setCookie('action_banner_show', 'Y', {expires: 43200, path: '/'});
		},15000);
		setTimeout(function(){
			$('.overl_action_banner .clos').css('display','flex');
		},18000)
	}
})
$(document).on('click', '.overl_action_banner .clos', function(){
	$('.overl_action_banner').hide();
})
$(document).on('click', '.sberbank__payment-link', function(){
	ym(22769200,'reachGoal','Continue-to-payment');
});