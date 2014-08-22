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
            var $input = $this.find('[name=cid][type=checkbox]');
            var input = $input[0];
            var value = input.value;
            //
            var $sameInput = $controlGroup.find('[name=cid][type=checkbox][value=' + value + ']');
            var length = $sameInput.length;
            while(length --) {
                $sameInput[length].checked = input.checked;
            }
            //
            if (input.checked) {
                var text = $this.find('.text').text();
                var tag = createTag(value, text);
                $tags.append(tag);
                cids += (value + ',');
            } else {
                var $tag = $tags.find('.tag[value=' + value + ']');
                $tag.remove();
                cids = cids.replace(value + ',', '');
            }
            createUrl();
            loadPage();
        });
        //取消分类
        $controlGroup.on('click', '.tag', function(e){
            var $this = $(this);
            var value = $this.attr('value');
            var $checkbox = $controlGroup.find('[name=cid][type=checkbox][value=' + value + ']');
            var length = $checkbox.length;
            while(length --) {
                $checkbox[length].checked = false;
            }
            $this.remove();
            cids = cids.replace(value + ',', '');
            createUrl();
            loadPage();
        });
        //选择数据类型
        $controlGroup.on('click', '[type=radio][name=type]', function(e){
            var $selected = $controlGroup.find('[type=radio][name=type]:checked');
            type = $selected[0].value;
            createUrl();
            loadPage();
        });
        //选择数据类型
        $controlGroup.on('click', '[type=radio][name=importance]', function(e){
            var $selected = $controlGroup.find('[type=radio][name=importance]:checked');
            var value = $selected[0].value;
            $livenews.attr('data-importance', value);
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
        var updatedAt = parseInt(data[0].updatedAt);
        if (updateTime == undefined || updatedAt > updateTime) {
            updateTime = updatedAt;
        }
        for (i = 0; i < l; i++) {
            var record = data[i];
            var mt = moment.unix(record.updatedAt);
            record.id = idPrefix + record.id;
            record.utm  = record.updatedAt;
            record.time = mt.format(timeFormat);
            record.date = mt.format(dateFormat);
            if (record.type == 'data' && record.codeType == 'json') {
                if (record.data['actual'] && record.data['actual'] !== '&nbsp;') {
                    if (record.data['forecast']  && record.data['forecast'] !== '&nbsp;') {
                        if (parseFloat(record.data['actual']) > parseFloat(record.data['forecast'])) {
                            record.data.trend = 'up';
                        } else if (parseFloat(record.data['actual']) < parseFloat(record.data['forecast'])) {
                            record.data.trend = 'down';
                        }
                    } else {
                        record.data['forecast'] = '- -';
                        if (record.data['previous']) {
                            if (parseFloat(record.data['actual']) > parseFloat(record.data['previous'])) {
                                record.data.trend = 'up';
                            } else if (parseFloat(record.data['actual']) < parseFloat(record.data['previous'])) {
                                record.data.trend = 'down';
                            }
                        }
                    }
                }
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

    function createTag(value, text) {
        return '<span class="tag" value="' + value + '"><i class="fa fa-times-circle"></i>' + text + '</span>';
    }


    init();

});
