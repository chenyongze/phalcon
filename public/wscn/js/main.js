//滚动条部分初始化
(function($){
    //页面右侧事实新闻
    $('#side-livenews').nanoScroller({
        alwaysVisible: true,
        preventPageScrolling: true,
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
        var $body = $('body');
        var $footer = $('#footer');
        var scrollMax = $body.height() - $footer.height() - $(window).height();
        //console.log(scrollMax);
        //
        $(document).on('scroll', function(e){
            var scroll = $body.scrollTop();
            //console.log(scroll);
            if (scroll > scrollMax) {
                $leftbar.addClass('moveout');
            } else {
                $leftbar.removeClass('moveout');
            }
        });
    }
})();

//搜索列表 高亮 关键字
$(function(){
    var $search = $('#search');
    if ($search.length == 0 || window.location.search.indexOf('?q=') == -1) {
        return;
    }
    var dom = $search[0];
    var keyword = decodeURI(window.location.search.substring(3));
    var html = dom.innerHTML;
    dom.innerHTML = html.replace(new RegExp(keyword, 'gm'), '<span class="keyword">' + keyword + '</span>');
});

(function($){

    /**
     * 导航栏高亮
     */
    var fullPathUrl = window.location.pathname + window.location.search;
    $('[data-active-url]').each(function(){
        var $item = $(this);
        var reg = new RegExp($item.attr("data-active-url"));
        if(reg.test(fullPathUrl)) {
            $item.addClass("active");
            //跳出循环
            //return false;
        }
    });

    //breaking-news
    $('[data-action=hide-breaking-news]').click(function(e){
        $('body').removeClass('show-breaking-news');
        return false;
    });

    //goto top 返回顶部
    $('#postbar .up').click(function(e){
        $('body').animate({
            scrollTop: 0
        }, 800);
        e.preventDefault();
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
                $('#hide-sidebar').show();
                $('#show-sidebar').hide();
            })
            .animate({
                right: '15px',
                top: '-=5'
            });
        e.preventDefault();
    });
    $('#hide-sidebar').on('click', function(e){
        $sidebar
            .animate({
                right: '-' + $sidebar.width(),
                top: '+=5'
            }, 500, function(){
                $sidebar.css('z-index', '1');
                $('#hide-sidebar').hide();
                $('#show-sidebar').show();
            })
            .animate({
                right: '15px',
                top: '-=5'
            });
        e.preventDefault();
    });
    // 页面侧边栏 end
    //

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

    //city select
    $('#address').citySelect({
        url : '/wscn/js/city.min.js',
        required: false
    });

})(jQuery);
