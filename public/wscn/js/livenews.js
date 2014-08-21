/**
 * Created by Sun on 14-7-24.
 */

$(function(){
    var $livenews = $('#livenews-list');
    if ($livenews.length == 0) {
        return;
    }
    var $content = $('#news-list > .content');
    var newsHeight = 16
    var margin = newsHeight - $content.height();
    $('.topbar').on('click', '[data-action]', function(e){
        var action = $(this).attr('data-action');
        var mTop = parseInt($content.css('margin-top'));
        if (action === 'up') {
            if (mTop == 0) {
                $content.stop().animate({
                    'margin-top': margin
                }, 300);
            } else {
                $content.stop().animate({
                    'margin-top': '+=' + newsHeight
                }, 300);
            }
        } else if (action === 'down') {
            if (mTop == margin) {
                $content.stop().animate({
                    'margin-top': 0
                }, 300);
            } else {
                $content.stop().animate({
                    'margin-top': '-=' + newsHeight
                }, 300);
            }
        }
    });
});

$(function(){

    var $livenews = $('#livenews-list');
    if ($livenews.length == 0) {
        return;
    }
    //
    var idPrefix = 'livenews-';
    //
    var $controlGroup;
    //
    var $moreControl;
    //
    var $tags;
    //
    var $body;


    var tmpl = template.compile($livenews.find('script[data-template]').html(), {escape: false});
    //
    var limit = 100;
    //
    var page = 1;
    //
    var timeFormat = 'HH:mm';
    var dateFormat = 'YYYY年MM月DD日 dddd';
    //实时新闻 分类id
    var cids;
    //实时新闻 数据分类
    var type;
    //
    var baseUrl = 'http://api.rebirth.wallstreetcn.com:80/v2/livenews?limit=50';
    //实时新闻 列表 url
    var url;
    //实时新闻 更新 url
    var baseUpdateUrl = 'http://api.rebirth.wallstreetcn.com:80/v2/livenews/realtime?limit=3';
    //
    var updateUrl;

    var update_timeout = 10000;
    //实时新闻 最新更新新闻的时间
    var updateTime;
    //实时新闻 获取详细信息url
    var infoUrl;

    function init() {
        initDom();
        initEvent();
        initData();
    }

    function initDom() {
        $controlGroup = $livenews.children('.control-group');
        $moreControl = $controlGroup.find('.more-content');
        $tags = $controlGroup.find('.tags');
        $body = $livenews.children('.body');
    }

    function initData() {
        createUrl();
        loadPage(1, update);
    }

    function initEvent() {
        //展示更多筛选
        $controlGroup.on('click', '.more', function(e){
            $moreControl.slideToggle();
            $(this).toggleClass('active');
        });
        //选择或取消分类
        $controlGroup.on('click', '.custom-checkbox', function(e){
            var $this = $(this);
            var $input = $this.find('[type=checkbox]');
            var input = $input[0];
            var name = input.name;
            //
            var $sameInput = $controlGroup.find('[type=checkbox][name=' + name + ']');
            var length = $sameInput.length;
            while(length --) {
                $sameInput[length].checked = input.checked;
            }
            //
            if (input.checked) {
                var text = $this.find('.text').text();
                var tag = createTag(name, text);
                $tags.append(tag);
                cids += (name + ',');
            } else {
                var $tag = $tags.find('.tag[name=' + name + ']');
                $tag.remove();
                cids = cids.replace(name + ',', '');
            }
            loadPage();
        });
        //取消分类
        $controlGroup.on('click', '.tag', function(e){
            var $this = $(this);
            var name = $this.attr('name');
            var $checkbox = $controlGroup.find('[type=checkbox][name=' + name + ']');
            var length = $checkbox.length;
            while(length --) {
                $checkbox[length].checked = false;
            }
            $this.remove();
            cids = cids.replace(name + ',', '');
            loadPage();
        });
    }

    function update() {
        var src = updateUrl
        if (updateTime) {
            src += '&min_updated=' + updateTime;
        }
        $.ajax({
            url : src,
            method : 'GET',
            dataType: 'jsonp'
        }).then(function(response) {
            var results = response.results;
            if (results.length) {
                var data = parseData(results);
                var $date = $body.children('.date').eq(0);
                var day = $date.text().trim().charAt(9);
                var i, l = data.length;
                var before = [];
                var after = [];
                for (i = 0; i < l; i++) {
                    var $item = $('#' + data[i].id);
                    if ($item.length) {
                        if ($item.attr('data-utm') !== data[i].utm) {
                            var singleData = [];
                            singleData[0] = data[i];
                            var html = tmpl({
                                checkDate: false,
                                records : singleData
                            });
                            $item.replaceWith(html);
                        } else if (data[i].status == 'deleted') {

                        }
                    } else if (item.date.charAt(9) != day) {
                        before.push(item);
                    } else {
                        after.push(item);
                    }
                }
                if (before.length) {
                    var html = tmpl({
                        checkDate: true,
                        records : before
                    });
                    $date.before(html);
                }
                if (after.length) {
                    var html = tmpl({
                        checkDate: false,
                        records : after
                    });
                    $date.after(html);
                }
            }
            setTimeout(update, update_timeout);
        }).fail(function(error) {
            setTimeout(update, update_timeout);
        });
    }

    function loadPage(page, callback) {
        page = page || 1;
        $.ajax({
            url : url + '&page=' + page,
            method : 'GET',
            dataType: 'jsonp'
        }).then(function(response) {
            var results = response.results;
            if (results.length) {
                var data = parseData(results);
                var html = tmpl({
                    checkDate: page == 1,
                    records : data
                });
                if (page > 1) {
                    $body.append(html);
                } else {
                    $body.html(html);
                }
            }
            if (typeof callback === 'function') {
                callback();
            }
        }).fail(function(error) {

        });
    }

    function showInfo() {

    }

    function parseData(data) {
        var records = [];
        var i;
        var l = data.length;
        var updateAt = parseInt(data[0].updateAt);
        if (updateTime == undefined || updateAt > updateTime) {
            updateTime = updateAt;
        }
        for (i = 0; i < l; i++) {
            var record = data[i];
            var mt = moment.unix(record.updatedAt);
            record.id += idPrefix;
            record.utm  = record.updatedAt;
            record.time = mt.format(timeFormat);
            record.date = mt.format(dateFormat);
            if () {

            }
            records.push(record);
        }
        return records;
    }

    function createUrl() {
        url = baseUrl;
        updateUrl = baseUpdateUrl;
        if (cids) {
            var query = '&cid=' + cids.substring(0, cids.length - 1);
            url += query;
            updateUrl += query;
        }
        if (type) {
            var query = '&type=' + type
            url += query;
            updateUrl += query;
        }
        url = encodeURI(url);
        updateUrl = encodeURI(updateUrl);
    }

    function createTag(name, text) {
        return '<span class="tag" name="' + name + '"><i class="fa fa-times-circle"></i>' + text + '</span>';
    }


    init();

});
