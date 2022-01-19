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
