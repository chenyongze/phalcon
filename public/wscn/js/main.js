(function($){
    var $livenews = $('#left-side-livenews');
    var $leftbar = $('#leftbar');
    var $leftSidebar = $('#leftbar > .sidebar');
    var $marketList = $('#left-market-list');
    /*var leftbar_height = $leftbar.height();
    var leftbar_offset = $leftbar.offset();
    var livenews_offset = $livenews.offset();
    var spacing = livenews_offset.top - leftbar_offset.top;
    var height = leftbar_height - spacing - 20;
    $livenews.height(height);*/

    //$marketList.height($marketList.height());
    $marketList.nanoScroller({
        preventPageScrolling: true,
        //alwaysVisible: true,
        iOSNativeScrolling: true
    });
    $leftSidebar.nanoScroller({
        preventPageScrolling: true,
        //alwaysVisible: true,
        iOSNativeScrolling: true
    });
    $('#right-side-livenews').nanoScroller({
        preventPageScrolling: true,
        //alwaysVisible: true,
        iOSNativeScrolling: true
    });
})(jQuery);

(function($){
    var timeout_show_related_info = null;
    var timeout_fold = null;
    var $leftbar = $('#leftbar');
    var $content = $('#content');
    $leftbar.on('mouseover', '[data-hover=unfold]', function(){
        clearTimeout(timeout_fold);
        $content.addClass('unfold');
    });
    $leftbar.on('mouseout', '[data-hover=unfold]', function(){
        clearTimeout(timeout_show_related_info);
        timeout_fold = setTimeout(function(){
            $content.removeClass('unfold');
        }, 300);
    });

    $leftbar.on('mouseover', '[data-hover=related-info]', function(){
        clearTimeout(timeout_show_related_info);
    });

    //breaking-news
    $('#breaking-news [data-action=close]').click(function(){
        $('body').removeClass('show-breaking-news');
        return false;
    });

    //
    var $sidebar = $('#sidebar');
    $('#show-sidebar').on('click', function(){
        $sidebar
            .animate({
                right: '-' + $sidebar.width(),
                top: '+=5'
            }, 500, function(){
                $sidebar.css('z-index', '4');
            })
            .animate({
                right: '15px',
                top: '-=5'
            });
    });
    $('#close-sidebar').on('click', function(){
        $sidebar
            .animate({
                right: '-' + $sidebar.width(),
                top: '+=5'
            }, 500, function(){
                $sidebar.css('z-index', '1');
            })
            .animate({
                right: '15px',
                top: '-=5'
            });
    });
})(jQuery);