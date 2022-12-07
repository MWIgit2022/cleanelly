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

function getClaculateForm(){
		var fields = $('.calculator_tabs span.active').data('fields');
		$('.calculator_form .inputs').html('');
		for (var key in fields){
			$('.calculator_form .inputs').append('<div><span>'+fields[key]+':</span><input required name="'+key+'" type="text"></div>');
		}
	}
	
	$(document).ready(function(){
		getClaculateForm();
	})
	
	$(document).on('click', '.calculator_tabs span:not(.active)', function(){
		$('.calculator_tabs span').removeClass('active');
		$(this).addClass('active');
		getClaculateForm();
		$('.result').html('');
	})
	
	$(document).on('keyup', '.calculator_form input', function(){
		$(this).val($(this).val().replace (/\D/, ''));
	});
	
	$(document).on('submit', '.calculator_form', function(e){
		e.preventDefault();
		var type= $(this).parent().find('.calculator_tabs span.active').data('arr');
		$.ajax({
			type: "POST",
			url: "/local/ajax/calculator_ajax.php",
			data: $(this).serialize()+'&type='+type,
			success: function(html){
				$('.calculator_form').find('.result').html(html);
			}
		})
	})
	
	$(document).on('click', '.show_calculator_sizes', function(){
		$.fancybox.open({
			href: '.calculator',
			type: 'inline',
			clickSlide : false,
			helpers: {
				overlay: { closeClick: false } 
			},
			touch: false,
		  });
	})