/**
 * Created by Sun on 14-7-22.
 */
(function(){
    var timeout_show_related_info = null;
    var timeout_fold = null;
    var $leftbar = $('#leftbar');
    var $content = $('#content');
    $leftbar.on('mouseover', '[data-hover=unfold]', function(e){
        clearTimeout(timeout_fold);
        $content.addClass('unfold');
    });
    $leftbar.on('mouseout', '[data-hover=unfold]', function(e){
        clearTimeout(timeout_show_related_info);
        timeout_fold = setTimeout(function(){
            $content.removeClass('unfold');
        }, 300);
    });

    $leftbar.on('mouseover', '[data-hover=related-info]', function(e){
        clearTimeout(timeout_show_related_info);
    });
    var $marketList = $('#left-market-list');
    $marketList.nanoScroller({
        preventPageScrolling: true,
        //alwaysVisible: true,
        iOSNativeScrolling: true
    });

    var marketService = {
        //
        $items: {},
        updateInterval: null,
        updateUrl: 'http://api.markets.wallstreetcn.com/v1/price.json?symbol=',
        //
        init: function() {
            this.initData();
            setInterval($.proxy(this.updateData, this), 10 * 1000);
        },

        //
        initData: function() {
            var items = $marketList.find('.item');
            var l = items.length;
            while(l--) {
                var $item = $(items[l]);
                $item.data({
                    prevClose : parseFloat($item.attr('data-prev-close'))
                });
                var symbol = $item.attr('data-symbol');
                this.$items[symbol] = $item;
                this.updateUrl += symbol + (l > 0 ? '_' : '');
            }
        },
        //
        updateData: function () {
            var _root = this;
            var $items = this.$items;
            if ($items == null) {
                console.log('function->updateData: the markets baseData is null');
                return;
            }
            $.ajax({
                url: _root.updateUrl,
                dataType: 'jsonp',
                success: function(data, request) {
                    var results = data['results'];
                    var l = results.length;
                    while(l--) {
                        var result = results[l];
                        var $item = $items[result.symbol];
                        var prevClose = $item.data('prevClose');
                        var price = result['price'];
                        var trend = '';
                        var diff = price - prevClose;
                        var diffPercent = (diff * 100 / prevClose).toFixed(2) + '%';
                        if (price > prevClose) {
                            trend = 'rise';
                            diffPercent = '+' + diffPercent;
                            diff = '+' + diff;
                        } else if (price < prevClose) {
                            trend = 'fall';
                        }
                        $item.attr('data-trend', trend);
                        $item.find('.value').text(price);
                        $item.find('.diff').text(diff);
                        $item.find('.diff-pct').text(diffPercent);
                    }
                }
            });
        },
        showInfo: function(symbol) {
            //显示数据
            this.showDatum(symbol);
            //显示图表
            this.showChart(symbol);
        },
        showDatum: function(symbol) {

        },
        showChart: function(symbol, type) {

        },

        clearInfoUpdateInterval: function() {

            console.log('clear the market Info Update Interval -------------------------------------');

            var interval = this.getInfoUpdateInterval();

            if (interval != null) {
                clearInterval(interval);
            }
        },

        clearListUpdateInterval: function() {

            console.log('clear the market list Update Interval -------------------------------------');

            var interval = this.getListUpdateInterval();

            if (interval != null) {
                clearInterval(interval);
            }
        }
    };
    marketService.init();
})();