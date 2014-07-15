(function(){
    var usf = window.userForms || {};
    usf.$element = $('#user-modal');
    usf.show = function(name){
        usf.$element.addClass('active');
        $('#user-modal-carousel .item.active').removeClass('active');
        switch(name) {
            case 'register' :
                $('#user-modal-carousel .item:eq(2)').addClass('active');
                break;
            case 'reset' :
                $('#user-modal-carousel .item:eq(0)').addClass('active');
                break;
            case 'login-connect' :
                $('#user-modal-carousel .item:eq(3)').addClass('active');
                break;
            case 'register-connect' :
                $('#user-modal-carousel .item:eq(4)').addClass('active');
                break;
            case 'login' :
            default:
                $('#user-modal-carousel .item:eq(1)').addClass('active');
                break;
        }
    };
    usf.onConnectSuccess = function(token, user) {
        usf.show('register-connect');
    };

    usf.onConnectFailed = function(error, errorMsg) {
        usf.$element.find('.item.active form').prepend('<div data-raw-message="' + error + '" class="alert alert alert-danger">' + errorMsg + '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>');
    };
    window.userForms = usf;
})();

//滚动条部分初始化
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
        //preventPageScrolling: true,
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
    //
    $(document).on('click', '[data-action=login]', function(e){
        $('#user-modal-carousel .item.active').removeClass('active');
        $('#user-modal-carousel .item:eq(1)').addClass('active');
    });
    //
    $(document).on('click', '[data-toggle=custom-modal]', function(e){
        var $this = $(this);
        var $target = $($this.attr('data-target'));
        $target.toggleClass('active');
    });
    $(document).on('keyup', function(e){
        switch(e.which) {
            case 27 :
                $('.custom-modal').removeClass('active');
                break;
        }
    });
    $('.custom-modal').on('click', function(e){
        if (e.target === this) {
            $(this).removeClass('active');
        }
    });

})(jQuery);
