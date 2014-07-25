
$(function ($) {
	
	var MARKET_QUOTES_API  = 'http://api.markets.wallstreetcn.com/v1/quotes.json',
		MARKET_QUOTE_API  = 'http://api.markets.wallstreetcn.com/v1/quote.json',
		MARKET_PRICE_API = 'http://api.markets.wallstreetcn.com/v1/price.json',
		assetInfo = null;
	
	var urlObj = WSCN_UTIL.url.parseQueryString();
	setBackUrl(urlObj.from);
	showDatum(urlObj.symbol);
	showChart(urlObj.symbol);
	
	$(".timerange-toolbar .button.nav").click(function(){
		$(".timerange-toolbar .button.nav").removeClass("active");
		$(this).addClass("active");
		
		showChart(urlObj.symbol,$(this).attr("data-type"));
	});
	
	$(".asset-chart .masking").click(function(){
		showFullScreen();
	});
	
	$(".full-screen .masking").click(function(){
		hideFullScreen();
	});
	
	
	/**Init**/
	if(!store.get("_wscn_mobile_marketdetail_hasShowTip")){
		showFullScreenTip();
		store.set("_wscn_mobile_marketdetail_hasShowTip","1");
	}
	
	/**Functions**/
	function showFullScreenTip(){
		new gmu.Popover(".asset-chart",{content:"点击下图可以全屏查看",placement:"top"}).show();
	}
	 
	function getScreenWidth() {
		var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
		return width;
	}

	function getScreenHeight() {
		var height = (window.innerHeight > 0) ? window.innerHeight : screen.height;
		return height;
	}
	 
	function showChart(symbol, type) {


		if (type == null) {
			var type = "minute";//app.Util.marketDefaultChartType;
		}

		//获取market chart 视图
		var $chart = $(".asset-chart iframe");
		//获取info视图的宽度
		var width = getScreenWidth();
		//设置chart的高度
		var height = Math.round(width*3/4);
		//获取chart视图的高度 (要减去 toolbar 的 高度)
		var maxHeight = $('.market .chart').height() - 40;
		//设定高度值
		height = height < maxHeight ? height : maxHeight;

		console.log('this is the maxHeight: ' + maxHeight);
		console.log('this is the height: ' + height);

		var url = 'http://markets.wallstreetcn.com/embed/chart?symbol=' + symbol +
			'&width='+ width +'&height=' + height + '&rows=50';
		switch (type) {
			case 'minute':
				url = url + '&interval=5';
				break;
			case 'day':
				url = url + '&type=candle&interval=1d';
				break;
			case 'week':
				url = url + '&type=candle&interval=1w';
				break;
			case 'month':
				url = url + '&type=candle&interval=mn';
				break;
			default :
				//todo 带添加
				url = url + '&interval=5';
		}
	   
	   $chart.attr({
			"height":height,
			"src":url
		}).css("height",height+"px");
		
		$(".asset-chart .wrapper,.asset-chart .masking").css("height",height+"px");
	}

	function showFullScreen(){
		//console.log("showFullScreen");
		var width = getScreenWidth(),height = getScreenHeight(),max,min,margin,orientation = document.body.parentNode.getAttribute("class");
		
		width > height ? (min = height,max = width) : (min = width,max = height);
		margin = Math.abs(height-width)/2
		 
		if(orientation == "portrait"){

			$(".full-screen iframe").attr({
				height:max,
				width:min,
				src:'http://markets.wallstreetcn.com/embed/hs?waterMark=1&mgnLeft='+(-margin)+'&mgnTop=' + margin + '&symbol=000001&deg=90&width=' + max + '&height=' + min +'&rows=50&interval=5&type=candlestick&chartTheme=light'
			});
		}
		else if(orientation == "landscape"){
			$(".full-screen iframe").attr({
				height:height,
				width:width,
				src:'http://markets.wallstreetcn.com/embed/hs?waterMark=1&mgnLeft=0&mgnTop=0&symbol=000001&width=' + width + '&height=' + height +'&rows=50&interval=5&type=candlestick&chartTheme=light'
			});
			
		}
		 
		$(".full-screen").css({"visibility":"visible","z-index":999});
		$(".full-screen .wrapper,.full-screen .masking").css("height",height+"px");
		$(".footerbar").css("display","none");
		 
	}

	function hideFullScreen(){
		console.log("hideFullScreen");
		$(".footerbar").css("display","");
		$(".full-screen").css({"visibility":"hidden","z-index":0});
		$(".full-screen .wrapper,.full-screen .masking").css("height","0px");
		$(".full-screen iframe").attr("src","about:blank");
	}

	function showDatum(symbol) {

		//var _root = this;
		//market 最外层视图

		//显示数据
		$.ajax({
			url: MARKET_QUOTE_API + '/' + symbol,
			dataType : 'jsonp',
			success: function(result, request) {
				assetInfo = result['results'];
				updateInfoData(symbol);
			}
		});
	}

	function updateInfoData(symbol) {

		//var _root = this;

		var base = assetInfo;

		$.ajax({
			url: MARKET_PRICE_API + '?symbol=' + symbol,
			dataType : 'jsonp',
			success: function(result, request) {

				var data = result['results'][0];

				data.title = base.title;
				data.prevClose = parseFloat(base.prevClose);
				data.open = parseFloat(base.open);


				//data.diffPercent = Math.round((data['price'] - data.prevClose) *10000 / data.prevClose)/100 + '%';
				data.diffPercent = ((data['price'] - data.prevClose) * 100 / data.prevClose).toFixed(2) + '%';

				var fixLength = base['numberFormat'].length - 2;
				data.diff = (data['price'] - data.prevClose).toFixed(fixLength);
				//data.diff = Math.round((data['price'] - data.prevClose) *100 / data.prevClose)/100;
				data.high = base['dayRangeHigh'];
				data.low = base['dayRangeLow'];
				if (data['price'] > data.prevClose) {
					data.trend = 'up';
					data.diffPercent = '+' + data.diffPercent;
					data.diff = '+' + data.diff;
				} else if (data['price'] < data.prevClose) {
					data.trend = 'down';
				} else {
					data.trend = '';
				}

				data.shareTitle = '@华尔街见闻 [' + data.title + '] 实时行情,值得你关注。' +
					'( 更多精彩尽在华尔街见闻:https://itunes.apple.com/us/app/hua-er-jie-jian-wen/id738227477 ) ';
				//data.shareUrl = app.Util.baseUrl + '?route=market_info_' + symbol;

				console.log('this is the market inf data ---------------------------');
				//console.log(data);
				//跟新视图数据
				updateDetailInfo(data);
				//console.log(data);
			}
		});

	}

	function updateDetailInfo(data){
		$(".trend").removeClass("up down").addClass(data.trend);
		$(".price").html(data.price);
		$(".diff").html(data.diff);
		$(".diffPercent").html(data.diffPercent);
		$(".prevClose .value").html(data.prevClose);
		$(".open .value").html(data.open);
		$(".high .value").html(data.high);
		$(".low .value").html(data.low);
		$(".title .value").html(data.title);
	}
	
	function setBackUrl(hash){
		$(".toolbar .back").attr("href",function(index,oldValue){
			return oldValue.indexOf("#") == -1 ? oldValue + "#" + (hash ? hash : "forex") : oldValue;
		});
	}
	
	
 }(Zepto));
 
 
