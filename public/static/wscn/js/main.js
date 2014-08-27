//滚动条部分初始化
$(function(){
    //页面经济日历
    $('.fc-list').fcl();
});

//页面右侧事实新闻
$(function(){
    var $dom = $('#side-livenews');
    if ($dom.length) {
        var $content = $dom.children('.content');
        var tmpl = template.compile($dom.find('script[data-template]').html(), {escape: false});
        init();
    }
    function init () {
        initData();
    }
    function initData() {
        $.ajax({
            url : 'http://api.rebirth.wallstreetcn.com:80/v2/livenews?limit=5',
            method : 'GET',
            dataType: 'jsonp'
        }).then(function(response) {
            var results = response.results;
            if (results.length) {
                var data = parseData(results);
                var html = tmpl({
                    records : data
                });
                $content.append(html);
            }
        }).fail(function(error) {
            //todo
            initData();
        });
    };
    function parseData(data) {
        var records = [];
        var i;
        var l = data.length;
        for (i = 0; i < l; i++) {
            var record = data[i];
            var mt = moment.unix(record.updatedAt);
            //record.id = idPrefix + record.id;
            record.utm  = record.updatedAt;
            record.time = mt.format('HH:mm');
            records.push(record);
        }
        return records;
    };
});

//收藏
$(function(){
    var loginUI = UserLogin.getInstance().getLoginUI();
    var usrManager = UserManager.getInstance();

    usrManager.onceLogin(function(user) {
        $("[data-toggle=collect]").each(function(){
            var btn = $(this);
            //var postId = btn.attr("data-post-id");
            var url = btn.attr("data-url");
            $.ajax({
                url : url
            }).then(function(response) {
                btn.attr("data-active", "true");
            }).fail(function(error) {
                btn.attr("data-active", "false");
            });
        });
    });

    $(document).on("click", "body[data-logon=false] [data-toggle=collect]", function(e) {
        loginUI.showModal();
        //loginUI.showMessage($(this).attr("data-message"), $(this).attr("data-message"));
        return false;
    });

    $(document).on("click", "body[data-logon=true] [data-active=false][data-toggle=collect]", function(){
        var btn = $(this);
        //var postId = btn.attr("data-post-id");
        var url = btn.attr("data-url");
        $.ajax({
            url : url,
            method : 'PUT'
        }).then(function(response) {
            btn.attr("data-active", "true");
        }).fail(function(error) {
        });
        return false;
    });

    $(document).on("click", "body[data-logon=true] [data-active=true][data-toggle=collect]", function(){
        var btn = $(this);
        //var postId = btn.attr("data-post-id");
        var url = btn.attr("data-url");
        $.ajax({
            url : url,
            method : 'DELETE'
        }).then(function(response) {
            btn.attr("data-active", "false");
        }).fail(function(error) {
        });
        return false;
    });

    //用户注册
    $(document).on('click', '[data-action=register]', function(e){
        loginUI.showModal('register');
        e.preventDefault();
    });

});

$(function(){
    //左侧行情
    var $leftbar = $('#leftbar');
    if ($leftbar.length) {
        //todo
        var contentTop = $('#content').offset().top;
        console.log('the content top is ' + contentTop);
        $leftbar.css('top', contentTop);
        var $window   = $(window);
        var $document = $(document);
        var $footer   = $('#footer');
        var scrollMax = $document.height() - $footer.height() - $window.height();
        var unwindPoint = 120  /*$('#content').offset().top*/;
        //console.log(scrollMax);
        //
        $window.on('scroll', function(e){
            var scroll = $document.scrollTop();
            //console.log(scroll);
            if (scroll > unwindPoint) {
                if (! $leftbar.hasClass('unwind')) {
                    $leftbar.addClass('unwind');
                    //$leftbar.trigger('height_change');
                }
            } else {
                if ($leftbar.hasClass('unwind')) {
                    $leftbar.removeClass('unwind');
                    //$leftbar.trigger('height_change');
                }
            }
            if (scroll > scrollMax) {
                $leftbar.addClass('moveout');
            } else {
                $leftbar.removeClass('moveout');
            }
        });
    }
});

//搜索列表 高亮 关键字
//$(function(){
//    var $search = $('#search-result');
//    if ($search.length == 0 || window.location.search.indexOf('?q=') == -1) {
//        return;
//    }
//    var dom = $search[0];
//    var keyword = decodeURI(window.location.search.substring(3));
//    var html = dom.innerHTML;
//    dom.innerHTML = html.replace(new RegExp(keyword, 'gm'), '<span class="search-highlight">' + keyword + '</span>');
//});

$(function(){

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

    //搜索框
    var $searchForm = $('#header .search-form');
    $('#header').on('click', '[data-toggle=search-form]', function(e){
        $searchForm.toggleClass('active');
        if ($searchForm.hasClass('active')) {
            $searchForm.find('[name=q]').focus();
        }
    });

    //goto top 返回顶部
    $('#postbar .up').click(function(e){
        $('html, body').animate({
            scrollTop: 0
        }, 300);
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


    // 页面侧边栏 start
    var $sidebar = $('#sidebar');
    $('#show-sidebar').on('click', function(e){
        $sidebar
            .animate({
                right: '-' + $sidebar.width(),
                top: '+=5'
            }, 500, function(){
                $sidebar.css('z-index', '4');
                $sidebar.addClass('active');
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
                $sidebar.removeClass('active');
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
    //组织modal表单 输入框 中 按下 左/右键 事件冒泡 触发 bootstrap carousel定义的滑动事件
    $('#user-modal-carousel').on('keydown', 'input', function(e){
        e.stopPropagation();
    });
    //switch  控件
    /*
    $('[data-toggle=switch]').click(function(){
        $(this).toggleClass('active');
    });
    */

    //city select
    $('#profile-form-location-group').citySelect({
        url : '/wscn/js/city.min.js',
        prov : $("#province").val(),
        city : $("#city").val(),
        dist : $("#state").val(),
        required: false
    });
    $('#profile-form-location-group').on('change', 'select', function(){
        console.log(1);
        var selector = $(this).attr('data-connect');
        $(selector).val($(this).val());
    });

});
