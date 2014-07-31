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
        console.log(token);
        console.log(user);
        if(user) {
        }
        if(token) {
            usf.show('register-connect');
            var site = {
                'weibo' : '微博',
                'tencent' : 'QQ'
            }
            usf.$element.find('[data-auth-avatar]').attr('src', token.remoteImageUrl);
            usf.$element.find('[data-auth-user]').html(token.remoteUserName);
            usf.$element.find('[data-auth-site]').html(site[token.adapterKey]);
        }
    };

    usf.onConnectFailed = function(error, errorMsg) {
        usf.$element.find('.item.active form').prepend('<div data-raw-message="' + error + '" class="alert alert alert-danger">' + errorMsg + '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button></div>');
    };
    window.userForms = usf;
})();

//滚动条部分初始化
(function($){
    //页面右侧事实新闻
    $('#right-side-livenews').nanoScroller({
        //alwaysVisible: true,
        iOSNativeScrolling: true
    });
    //页面经济日历
    $('.fc-list').fcl();
})(jQuery);

(function(){
    //左侧行情
    var $leftbar = $('#leftbar');
    if ($leftbar.length) {
        //var height = $leftbar.height();
        var $document = $(document);
        var $footer = $('#footer');
        var scrollMax = $document.height() - $footer.height() - $(window).height();
        console.log(scrollMax);
        $document.on('scroll', function(e){
            var scroll = $document.scrollTop();
            console.log(scroll);
            if (scroll > scrollMax) {
                $leftbar.addClass('moveout');
            } else {
                $leftbar.removeClass('moveout');
            }
        });
    }
})();

(function($){

    //breaking-news
    $('[data-action=hide-breaking-news]').click(function(e){
        $('body').removeClass('show-breaking-news');
        return false;
    });

    //点赞
    $('[data-action=endorse]').on('click', function(e){
        var $this = $(this);
        if ($this.hasClass('active')) {
            return;
        }
        var text = parseInt($this.text());
        text ++ ;
        $this.text(text);
        $this.addClass('active');
        e.preventDefault();
    });
    //收藏
    $('[data-action=collect]').on('click', function(e){
        $(this).toggleClass('active');
        e.preventDefault();
    });

    // 页面侧边栏 start
    var $sidebar = $('#sidebar');
    $('#show-sidebar').on('click', function(e){
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
        e.preventDefault();
    });
    $('#close-sidebar').on('click', function(e){
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
        e.preventDefault();
    });
    // 页面侧边栏 end
    //
    $(document).on('click', '[data-action=login]', function(e){
        userForms.show();
        return false;
    });
    // custom-modal
    $(document).on('click', '[data-action=custom-modal]', function(e){
        var $this = $(this);
        var $target = $($this.attr('data-target'));
        $target.addClass('active');
        e.preventDefault();
    });
    $(document).on('click', '[data-action=close-custom-modal]', function(e){
        var $this = $(this);
        var $target = $($this.attr('data-target'));
        $target.removeClass('active');
        e.preventDefault();
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
    $('#user-modal-carousel > .carousel-inner > .item').on('click', function(e){
        if (e.target === this) {
            $('#user-modal').removeClass('active');
        }
    });
    //switch  控件
    /*
    $('[data-toggle=switch]').click(function(){
        $(this).toggleClass('active');
    });
    */
})(jQuery);
