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
    var $controlGroup;
    //
    var $moreControl;
    //
    var $tags;
    //
    var $body;
    //
    var tmpl = template.compile($livenews.find('script[data-template]').html(), {escape: false});


    //
    var option = {
        page : 1,
        timeFormat: 'HH:mm',
        dateFormat: 'YYYY年MM月DD日 dddd',
        cids: '',
        type: '',
        importance: '',
        baseUrl: 'http://api.rebirth.wallstreetcn.com:80/v2/livenews?limit=40',
        baseUpdateUrl: 'http://api.rebirth.wallstreetcn.com:80/v2/livenews/realtime?limit=3',
        url: 'http://api.rebirth.wallstreetcn.com:80/v2/livenews?limit=40',
        updateUrl: 'http://api.rebirth.wallstreetcn.com:80/v2/livenews/realtime?limit=3',
        updateTimeout: 10000
    };
    var uri = {
        baseUrl: location.href.replace(/\?.*|#.*/, ''),
        search: location.search.substring(1),
        add: function(name, value){
            if (this.search.length) {
                this.search += '&' + name + '=' + value;
            } else {
                this.search = name + '=' + value;
            }
            option.url = option.baseUrl + '&' + this.search;
            option.updateUrl = option.baseUpdateUrl + '&' + this.search;
            try{
                history.replaceState(null, '', this.baseUrl + '?' + this.search);
            } catch(err) {
                location.href = this.baseUrl + '#' + this.search;
            }
        },
        remove: function(name, value) {

            if (value) {
                this.search = this.search.replace(name + '=' + value, '');
            } else {
                var reg = new RegExp(name + '=[\\w,]+');
                this.search = this.search.replace(reg, '');
            }
            this.search = this.search.replace(/&+/g, '&').replace(/^&|&$/g, '');

            if (this.search.length) {
                option.url = option.baseUrl + '&' + this.search;
                option.updateUrl = option.baseUpdateUrl + '&' + this.search;
            } else {
                option.url = option.baseUrl;
                option.updateUrl = option.baseUpdateUrl;
            }
            try{
                history.replaceState(null, '', this.baseUrl + '?' + this.search);
            } catch(err) {
                location.href = this.baseUrl + '#' + this.search;
            }
        },
        attr: function(name, value) {
            if (this.search.indexOf(name) !== -1) {
                var reg = new RegExp(name + '=[\\w,]+');
                this.search = this.search.replace(reg, name + '=' + value);
                option.url = option.baseUrl + '&' + this.search;
                option.updateUrl = option.baseUpdateUrl + '&' + this.search;
                try{
                    history.replaceState(null, '', this.baseUrl + '?' + this.search);
                } catch(err) {
                    location.href = this.baseUrl + '#' + this.search;
                }
            } else {
                this.add(name, value);
            }
        }
    };
    //实时新闻 最新更新新闻的时间
    var updateTime;
    //实时新闻 获取详细信息url
    var infoUrl;

    function init() {
        initDom();
        initEvent();
        initUrl();
        initData();
    }

    function initUrl() {
        var search = location.search;
        if(search) {
            search = search.substring(1);
            var args = search.split('&');
            var l = args.length;
            while(l--) {
                var map = args[l].split('=');
                $controlGroup.find('[name="' + map[0] + '"][value="' + map[1] + '"]').eq(0).trigger('init');
            }
            option.url = option.baseUrl + '&' + search;
            option.updateUrl = option.baseUpdateUrl +  '&' + search;
        }
    }

    function initDom() {
        $controlGroup = $livenews.children('.control-group');
        $moreControl = $controlGroup.find('.more-content');
        $tags = $controlGroup.find('.tags');
        $body = $livenews.children('.body');
    }

    function initData() {
        loadPage(1, update);
    }

    function initEvent() {
        //展示更多筛选
        $controlGroup.on('click', '.more', function(e){
            $moreControl.slideToggle();
            $(this).toggleClass('active');
        });

        $controlGroup.on('init', '[name="cid[]"][type=checkbox]', function(e){
            var $input = $(this);
            var input = $input[0];
            input.checked = true;
            var name = input.name;
            var value = input.value;
            //找出相同的checkbox
            var $sameInput = $controlGroup.find('[name="cid[]"][type=checkbox][value="' + value + '"]');
            var length = $sameInput.length;
            while(length --) {
                $sameInput[length].checked ;
            }
            var text = $input.parent().find('.text').text();
            var tag = createTag(value, text);
            $tags.append(tag);
        });
        $controlGroup.on('init', '[type=radio][name=type]', function(e){
            this.checked = true;
        });
        $controlGroup.on('init', '[type=radio][name=importance]', function(e){
            this.checked = true;
        });


        //删除 分类
        $controlGroup.on('click', '.tag', function(e){
            var $this = $(this);
            var value = $this.attr('value');
            var $checkbox = $controlGroup.find('[name="cid[]"][type=checkbox][value="' + value + '"]');
            var length = $checkbox.length;
            while(length --) {
                $checkbox[length].checked = false;
            }
            $this.remove();
            uri.remove('cid[]', value);
            loadPage();
        });

        //选择或取消分类
        $controlGroup.on('click', '[name="cid[]"][type=checkbox]', function(e){

            var $input = $(this);
            var input = $input[0];
            var name = input.name;
            var value = input.value;
            //找出相同的checkbox
            var $sameInput = $controlGroup.find('[name="cid[]"][type=checkbox][value="' + value + '"]');
            var length = $sameInput.length;
            while(length --) {
                $sameInput[length].checked = input.checked;
            }
            //
            if (input.checked) {
                var text = $input.parent().find('.text').text();
                var tag = createTag(value, text);
                $tags.append(tag);
                uri.add(name,value);
            } else {
                var $tag = $tags.find('.tag[value="' + value + '"]');
                $tag.remove();
                uri.remove(name,value);
            }
            loadPage();
        });

        //选择数据类型
        $controlGroup.on('click', '[type=radio][name=type]', function(e){
            var $selected = $controlGroup.find('[type=radio][name=type]:checked');
            var value = $selected[0].value;
            if (value) {
                uri.attr('type', value);
            } else {
                uri.remove('type');
            }
            loadPage();
        });
        //选择 重要性
        $controlGroup.on('click', '[type=radio][name=importance]', function(e, noLoad){
            var $selected = $controlGroup.find('[type=radio][name=importance]:checked');
            var value = $selected[0].value;
            if (value) {
                uri.attr('importance', value);
            } else {
                uri.remove('importance');
            }
            loadPage();
        });
    }

    function update() {
        $.ajax({
            url : option.updateUrl,
            method : 'GET',
            dataType: 'jsonp',
            data: {
                min_updated: updateTime
            }
        }).then(function(response) {
            var results = response.results;
            if (results.length) {
                var data = parseData(results);
                var $date = $body.children('.date').first();
                var i, l = data.length;
                for (i = 0; i < l; i++) {
                    var $item = $('#' + data[i].id);
                    if ($item.length) {
                        $item.remove();
                        if (data[i].status == 'deleted') {
                            data.splice(i,1);
                        }
                    }
                }
                if (data.length) {
                    var html = tmpl({
                        day: '',
                        records : data
                    });
                    $date.replaceWith(html);
                }
            }
            setTimeout(update, option.updateTimeout);
        }).fail(function(error) {
            //setTimeout(update, option.updateTimeout);
        });
    }

    function loadPage(page, callback) {
        page = page || 1;
        $.ajax({
            url : option.url,
            method : 'GET',
            dataType: 'jsonp',
            data: {
                page: page
            }
        }).then(function(response) {
            var results = response.results;
            if (results.length) {
                var data = parseData(results);
                var html;
                if (page > 1) {
                    //
                    var day = $body.children().last().attr('data-day');
                    html = tmpl({
                        day: day,
                        records : data
                    });
                    $body.append(html);
                } else {
                    html = tmpl({
                        day: '',
                        records : data
                    });
                    $body.html(html);
                }
            }
            if (typeof callback === 'function') {
                callback();
            }
        }).fail(function(error) {
            //todo
            loadPage(page, callback);
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
            //record.id = idPrefix + record.id;
            record.utm  = record.updatedAt;
            record.time = mt.format(option.timeFormat);
            record.date = mt.format(option.dateFormat);
            record.day = mt.format('YYYY-MM-DD');
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

    function createTag(value, text) {
        return '<span class="tag" value="' + value + '"><i class="fa fa-times-circle"></i>' + text + '</span>';
    }


    init();

});
