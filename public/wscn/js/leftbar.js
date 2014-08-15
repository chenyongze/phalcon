(function(root, factory) {
    if (typeof define === "function" && define.amd) {
        //For requirejs
        define('widget', function (require, exports, module) {
            var $ = require('jquery');
            return factory(root, exports, $);
        });
    } else if (typeof exports !== "undefined") {
        //For nodejs
        var $ = require("jquery");
        factory(root, exports, $);
    } else {
        //exports as global
        root.widget = factory(root, {}, root.jQuery);
    }
}(this, function(root, widget, $) {
    "use strict";
    widget = function(){
    };

    widget.foo = function(){
        console.log('widget compatible version foo', $.now());
    };

    widget.prototype = {
        bar: function(){
            console.log('widget compatible version bar', $.now());
        }
    };

    return widget;
}));


/**
 * Created by Sun on 14-7-22.
 */
(function(){
    //just for test
    var TEST_DATUM_1 = $('#test-leftbar-datum-1').html();
    var TEST_DATUM_2 = $('#test-leftbar-datum-2').html();
    var TEST_DATUM_INDEX = 0;

    var timeout_fold = null;
    var timeout_show = null;
    var timeout_show_datum = null;
    var timeout_show_chart = null;
    var interval_update = null;
    //页面中部内容区
    var $content;
    //左侧栏
    var $leftbar;
    //左侧栏行情列表dom
    var $marketList;
    //行情的相关信息dom
    var $marketInfo;
    //行情的图表
    var $marketChart;
    //行情相关的新闻信息
    var $marketDatum;
    //行情资产jquery对象集合
    var $items;
    //行情更新数据url
    var updateUrl = 'http://api.markets.wallstreetcn.com/v1/price.json?symbol=';

    //
    var cache = {};


    /**
     * 收起 侧边拦
     */
    function fold() {
        if ($leftbar.hasClass('docking')) {
            return;
        }
        clearTimeout(timeout_fold);
        timeout_fold = setTimeout(function(){
            $content.removeClass('unfold');
        }, 200);
    };
    /**
     *
     */
    function updateData () {

        if ($items == null) {
            console.log('function->updateData: the markets baseData is null');
            return;
        }
        $.ajax({
            url: updateUrl,
            dataType: 'jsonp',
            success: function(data, request) {
                var results = data['results'];
                var l = results.length;
                while(l--) {
                    var result = results[l];
                    var $item = $items[result.symbol];
                    var prevClose = $item.data('prevClose');
                    var numFixed = $item.data('numFixed');
                    var price = result['price'];
                    var trend = '';
                    var diff = price - prevClose;
                    var diffPercent = (diff * 100 / prevClose).toFixed(2) + '%';
                    //
                    diff = diff.toFixed(numFixed);
                    if (price > prevClose) {
                        trend = 'rise';
                        diffPercent = '+' + diffPercent;
                        diff = '+' + diff;
                    } else if (price < prevClose) {
                        trend = 'fall';
                    }
                    //
                    price = price.toFixed(numFixed);

                    $item.attr('data-trend', trend);
                    $item.find('.value').text(price);
                    $item.find('.diff').text(diff);
                    $item.find('.diff-pct').text(diffPercent);
                }
            }
        });
    };

    /**
     *
     * @param symbol
     */
    function showInfo(symbol, $target) {
        //todo
        $content.addClass('unfold');
        //
        if ($target.hasClass('active') || $marketInfo.data('symbol') === symbol || $marketInfo.hasClass('loading')) {
            return;
        }
        $marketInfo.data({
            symbol: symbol
        });
        //todo
        $marketInfo.addClass('loading');
        showChart(symbol);
        showDatum($target);
        //
    };
    function showChart(symbol) {
        //todo
        var src = $marketChart.attr('src');
        src = src.replace(/symbol=\w+/, 'symbol=' + symbol);
        $marketChart.attr('src', src);
    };
    function showDatum($target) {
        var url = $target.attr('data-source');
        var symbol = $target.attr('data-symbol');
        //todo
        if (cache[symbol]) {
            $marketDatum.html(cache[symbol]);
            //初始化滚动条
            $marketInfo.nanoScroller({
                preventPageScrolling: true,
                alwaysVisible: true
            });
            //
            $marketInfo.removeClass('loading');
            $marketList.find('.active').removeClass('active');
            $target.addClass('active');
        } else {
            $.ajax({
                url: url ,
                dataType: 'html' ,
                success : function(data) {
                    cache[symbol] = data;
                    $marketDatum.html(data);
                    //初始化滚动条
                    $marketInfo.nanoScroller({
                        preventPageScrolling: true,
                        alwaysVisible: true
                    });
                    //
                    $marketInfo.removeClass('loading');
                    $marketList.find('.active').removeClass('active');
                    $target.addClass('active');
                }
            });
        }
    };

    function initDom() {
        //页面中部内容区
        $content = $('#content');
        //左侧栏
        $leftbar = $('#leftbar');
        //左侧栏行情列表dom
        $marketList = $('#left-market-list');
        //行情的相关信息dom
        $marketInfo = $leftbar.children('.sidebar');
        //行情的图表
        $marketChart = $leftbar.find('.chart');
        //行情相关的新闻信息
        $marketDatum = $leftbar.find('.datum');
        //行情列表初始化滚动条
        $marketList.nanoScroller({
            preventPageScrolling: true,
            //alwaysVisible: true,
            iOSNativeScrolling: true
        });
    }

    function initData() {
        $items = {};
        var items = $marketList.find('.item');
        var l = items.length;
        while(l--) {
            var $item = $(items[l]);
            $item.data({
                prevClose: parseFloat($item.attr('data-prev-close')),
                numFixed: parseInt($item.attr('data-num-fixed'))
            });
            var symbol = $item.attr('data-symbol');
            $items[symbol] = $item;
            updateUrl += symbol + (l > 0 ? '_' : '');
        }
        updateData();
        setInterval(updateData, 10 * 1000);
    }

    function initEvent() {
        $leftbar.children('.custom-close').click(function(){
            $leftbar.removeClass('docking');
            //todo
            $content.removeClass('unfold');
        });

        //
        $leftbar.on('mouseenter', '[data-hover=unfold]', function(e){
            clearTimeout(timeout_fold);
            $content.addClass('unfold');
        });
        $leftbar.on('mouseleave', '[data-hover=unfold]', function(e){
            fold();
        });
        //固定 侧边拦
        $leftbar.on('click', '[data-hover=related-info]', function(e){
            $leftbar.addClass('docking');
            e.preventDefault();
        });
        //监听显示行情对应的相关信息事件
        $leftbar.on('mouseenter', '[data-hover=related-info]', function(e){
            clearTimeout(timeout_show);
            clearTimeout(timeout_fold);
            var $target = $(this);
            var symbol = $target.attr('data-symbol');
            //showInfo(symbol, $target);
            timeout_show = setTimeout($.proxy(showInfo, null, symbol, $target), 200);
        });
        //
        $leftbar.on('mouseleave', '[data-hover=related-info]', function(e){
            clearTimeout(timeout_show);
            fold();
        });
        //实时新闻列表 展开
        $leftbar.on('click', '.livenews-list .sign', function(e){
            var $content = $(this).parent();
            $content.toggleClass('fullsize');
            //更新 滚动条
            $marketInfo.nanoScroller();
        });

        //监听 leftbar 过度变化，更新滚动条
        $leftbar.on('transitionend', function(e){
            console.log('$leftbar transitionend');
            $marketList.nanoScroller();
            $marketInfo.nanoScroller();
        });
    }

    function init() {
        initDom();
        initData();
        initEvent();
    }

    if ($('#left-market-list').length) {
        init();
    }




})();