function declOfNum(number, titles) {
    cases = [2, 0, 1, 1, 1, 2];
    return titles[(number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5]];
}

function contactsToggleChange(elem) {
    $('.round-contacts-toggle').attr('style', '');
    if (elem.val() == 'type_list') {
        $('.contacts-page-map').hide();
        $('.item-views-wrapper.contacts').show();
    } else {
        $('.contacts-page-map').show();
        $('.item-views-wrapper.contacts').hide();
    }
}

$(document).ready(function () {
    $('.favorites').click(function () {
        $.ajax({
            method: "POST", // метод HTTP, используемый для запроса
            url: "/local/ajax/execFavorites.php", // строка, содержащая URL адрес, на который отправляется запрос
            dataType: "JSON",
            data: { // данные, которые будут отправлены на сервер
                productId: $(this).attr('data-id'),
            },
            success: function (msg) { // функции обратного вызова, которые вызываются если AJAX запрос выполнится успешно (если несколько функций, то необходимо помещать их в массив)
                if (msg.success) {
                    if (msg.add) {
                        //изменить иконку на удалить из избранного
                        $('#productId-' + msg.productId).children('svg').children('g').children('g').children('#fav_color').attr('class', 'icon_favorite');
                    } else {
                        //изменить иконку на добавить в избранное
                        $('#productId-' + msg.productId).children('svg').children('g').children('g').children('#fav_color').attr('class', 'icon_unfavorite');
                    }
                    $('.favoritesCount').text(msg.count);
                }
            },
        })
    })
    
    $('.main-about_link a[href="#show-more"]').click(function () {
        $('#show-more').show();
        $(this).hide();

        return false;
    });

    $('[name="licenses_order"]').prop('checked', true);

    $('.fancy2').fancybox({
        autoScale: false,
        fitToView: false,
        openEffect: 'fade',
        closeEffect: 'fade',
        nextEffect: 'fade',
        prevEffect: 'fade',
        tpl: {
            closeBtn: '<a title="' + BX.message('FANCY_CLOSE') + '" class="fancybox-item fancybox-close" href="javascript:;"></a>',
            next: '<a title="' + BX.message('FANCY_NEXT') + '" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
            prev: '<a title="' + BX.message('FANCY_PREV') + '" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'
        },
    });


    if ($('.front_page').length > 0) {
        var mainslider = $('.top_slider_wrapp .flexslider, .top_slider_wrapp .flexslider .slides>li, .top_slider_wrapp .flexslider .slides>li td');
        if ($(window).width() < 800) {
            mainslider.css('min-height', $(window).height() - 63);
        }
    }

    if ($(".counter_block:not(.basket) .plus").length > 0) {
        var timeoutMaxTitle;
        var isChanged = false;
        $(document).on("change", ".counter_block:not(.basket) input", function () {
            isChanged = true;
        });
        $(document).on("click", ".counter_block:not(.basket) .plus", function () {
            if (!isChanged) {
                var maxNotify = $(this).parents('.counter_block').find('.counter_block_ismax');
                maxNotify.show();

                clearTimeout(timeoutMaxTitle);
                timeoutMaxTitle = setTimeout(function () {
                    maxNotify.hide();
                }, 1000);
            }

            isChanged = false;
        });
    }

    if ($(".counter_block:not(.basket) .plus").length > 0) {
        var timeoutMaxTitle;
        var isChanged = false;
        $(document).on("change", ".counter_block:not(.basket) input", function () {
            isChanged = true;
        });
        $(document).on("click", ".counter_block:not(.basket) .plus", function () {
            if (!isChanged) {
                var maxNotify = $(this).parents('.counter_block').find('.counter_block_ismax');
                maxNotify.show();

                clearTimeout(timeoutMaxTitle);
                timeoutMaxTitle = setTimeout(function () {
                    maxNotify.hide();
                }, 1000);
            }

            isChanged = false;
        });
    }
 
    $('input[name="contacts-toggle"]').on('change', function () {
        contactsToggleChange($(this));
    });

    $('.toggle-contacts-pill').on('click', function() {
        if($('input[name="contacts-toggle"]').length > 0) {
            let uncheckedID = $('input[name=contacts-toggle]:unchecked').attr('id');
            $(this).parent().children('label[for='+uncheckedID+']').click();
        }
    });

    $('.round-contacts-toggle').on('click', function() {
        if($('input[name="contacts-toggle"]').length > 0) {
            let uncheckedID = $('input[name=contacts-toggle]:unchecked').attr('id');
            $(this).parent().children('label[for='+uncheckedID+']').click();
        }
    });
    
    var toggleStartLeft = 0;
    if($('.round-contacts-toggle').length > 0) {
        $('.round-contacts-toggle').draggable({
            axis: 'x',
            containment: 'parent',
            start: function() {
                let switchHandle = $('.round-contacts-toggle');
                toggleStartLeft = parseInt(switchHandle.css('left'));
            },
            stop: function() {
                let switchHandle = $('.round-contacts-toggle');
                let sizeToggle = switchHandle.width();
                let sizePill = switchHandle.parent().width();
                if(toggleStartLeft > sizeToggle / 2) {
                    switchHandle.animate({
                        left: 2+'px'
                    }, 100, function() {
                        if($('.contacts-label-map').length > 0) {
                            $('.contacts-label-map').click();
                        }
                    });
                } else {
                    let leftPx = sizePill - sizeToggle - 2;
                    switchHandle.animate({
                        left: leftPx +'px',
                    }, 100, function() {
                        if($('.contacts-label-list').length > 0) {
                            $('.contacts-label-list').click();
                        }
                    });
                }
            }
          });
    };
    
    if ($('input[name="contacts-toggle"]').length > 0) {
        let toggleElem = $('input[id="' + $('input[name="contacts-toggle"]').val() + '"]');
        if(toggleElem.length > 0) {
            toggleElem.click();
        }
    };
    $('#city').on('change', function () {
        let toggleElem = $('input[id="' + $('input[name="contacts-toggle"]').val() + '"]');
        if(toggleElem.length > 0) {
            if(!($('input[id=type_list]').prop('checked'))){
                $('input[id=type_list]').prop('checked',true);
            }
            if($('input[name="contacts-toggle"]:unchecked').val()=="type_map"){
                setTimeout(function(){
                    contactsToggleChange(toggleElem);
                    if(!($('input[id=type_list]').prop('checked'))){
                        $('input[id=type_list]').prop('checked',true);
                    }
                },1000);
            }
        }
    });
    $('#region').on('change', function () {
        let toggleElem = $('input[id="' + $('input[name="contacts-toggle"]').val() + '"]');
        if(toggleElem.length > 0) {
            if(!($('input[id=type_list]').prop('checked'))){
                $('input[id=type_list]').prop('checked',true);
            }
            if($('input[name="contacts-toggle"]:unchecked').val()=="type_map"){
                setTimeout(function(){
                    contactsToggleChange(toggleElem);
                    if(!($('input[id=type_list]').prop('checked'))){
                        $('input[id=type_list]').prop('checked',true);
                    }
                },1000);
            }
        }
    });
	
	



function rutarget_addToCart() {
    var _rutarget = window._rutarget || [];
    _rutarget.push({'event': 'addToCart'});
}

$(document).ready(function () {

    $('body').on('click', '.basket-item-amount-btn-plus', function() {
        rutarget_addToCart();
    });
    $('body').on('click', '.element_detail_text_wish_link', function() {
        var _rutarget = window._rutarget || [];
        _rutarget.push({'event': 'addToCompare'});
    });
});

$(document).ready(function () {
    if (!$.cookie('new-client-form') && !$.cookie('first-open-form')) {
        setTimeout(function () {
            ($(document).find(".new-client-form")).click();
            var date = new Date();
            date.setTime(date.getTime() + $(".new-client-form").data("showtimesecond"));

            $.cookie('first-open-form', 1, { expires: date });

            if (!$.cookie('second-open-form')) 
                $.cookie('second-open-form', 1); 
            else 
                $.cookie('new-client-form', 1); 
                
        }, $(".new-client-form").data("showtime"));
    }
});


$(document).ready(function() {
    $('.catalog_item:not(.-has-sale) .sale_block').not(':empty').each(function() {
        $(this).closest('.catalog_item').addClass('-has-sale').siblings('.catalog_item').addClass('-has-sale');
    });
});

$(document).ready(function()
 {$('.main-about_link a[href="#show-more"]').click(function()
 {$('#show-more').show();$(this).hide();return!1});$('[name="licenses_order"]').prop('checked',!0);$('.fancy2').fancybox({autoScale:!1,fitToView:!1,openEffect:'fade',closeEffect:'fade',nextEffect:'fade',prevEffect:'fade',tpl:{closeBtn:'<a title="'+BX.message('FANCY_CLOSE')+'" class="fancybox-item fancybox-close" href="javascript:;"></a>',next:'<a title="'+BX.message('FANCY_NEXT')+'" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',prev:'<a title="'+BX.message('FANCY_PREV')+'" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>'},});if($('.front_page').length>0)
 {var mainslider=$('.top_slider_wrapp .flexslider, .top_slider_wrapp .flexslider .slides>li, .top_slider_wrapp .flexslider .slides>li td');if($(window).width()<800) {mainslider.css('min-height', $(window).height() - 63)}}
  jQuery('.flexslider').each(function () {jQuery(this).find('ul.slides li:empty div.has-item').remove();if ((jQuery(this).find('ul.slides li div.has-item').length <= 4) && ($(window).width() >= '1201')) {jQuery(this).find('ul.slides li:empty div.has-item').remove();jQuery(this).find('.flex-direction-nav').remove();} else if((jQuery(this).find('ul.slides li div.has-item').length <= 3) && ($(window).width() <= '1200' && $(window).width() >= '993')) {jQuery(this).find('ul.slides li:empty div.has-item').remove();jQuery(this).find('.flex-direction-nav').remove();} else if ((jQuery(this).find('ul.slides li div.has-item').length <= 2) && ($(window).width() <= '992' && $(window).width() >= '601')) {jQuery(this).find('ul.slides li:empty div.has-item').remove();jQuery(this).find('.flex-direction-nav').remove();} else if ((jQuery(this).find('ul.slides li div.has-item').length <= 1) && $(window).width() <= '601') {jQuery(this).find('ul.slides li:empty div.has-item').remove();jQuery(this).find('.flex-direction-nav').remove();} else {}});})
  $(document).on('keydown', 'input[type="tel"].phone', function(event) {
    if($(this).data('_inputmask_opts') && /[0-9]/gi.test(event.key)) {
      var value = $(this).inputmask('unmaskedvalue');
      if(value.length >= 10 && (value[0] == '7' || value[0] === '8')) {
        value = value.substring(1) + event.key;
      }
      this.value = value;
    }
  });
  $(document).on('paste', 'input[type="tel"].phone', function(event) {
    if($(this).data('_inputmask_opts')) {
      var paste = (event.originalEvent.clipboardData || window.clipboardData);
      if(paste) {
        paste = paste.getData('text');
        paste = paste.replace(/[^0-9]/gi, "");
        if(paste.length >= 10) {
          this.value = paste.substr(-10);
        }
      }
    }
  });

document.addEventListener("DOMContentLoaded", function() {
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
    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
});