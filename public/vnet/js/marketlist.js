
$(function ($) {
	var MARKET_QUOTE_API  = 'http://api.markets.wallstreetcn.com/v1/quotes.json',
		MARKET_PRICE_API = 'http://api.markets.wallstreetcn.com/v1/price.json',
		assetList = null;
		
	$(".market.nav").click(function(){
		$('.markets.view').attr('data-type', $(this).attr("data-type"));
		window.location.hash = $(this).attr("data-type");

	});
	
	$(".markets.view").on('click','tr',function(){
        return false;
		document.location = "/markets/" + $(this).attr("data-symbol") + "?from=" + getHash();
	});
	
	$('.markets.view').attr('data-type', getHash());
	
	/**Init**/
	initData();
	setInterval(updateData, 1000*10);
	
	/**Functions**/
	function initData() {

		//var _root = this;

		$.ajax({
			url: MARKET_QUOTE_API,
			dataType : 'jsonp',
			success: function(result, request) {

			   assetList = result['results'];
			   updateData();

			},
			failure: function() {
				console.log('-------------------------------------the market init data has failure, try again!!');
				//再调用一次，直到成功为止
				initData();
			}

		});
	}

	function updateData() {

		//var _root = this;

		var baseData = assetList;

		if (baseData == null) {
			console.log('function->updateData: the markets baseData is null');
			return;
		}

		var results = [];

		 $.ajax({
			url: MARKET_PRICE_API,
			dataType : 'jsonp',
			success: function(result, request) {

				var data = result['results'];

				for (var i=0; i<baseData.length; i++) {

					for (var j=0; j<data.length; j++) {

						if (baseData[i]['symbol'] === data[j]['symbol']) {
							var base = baseData[i];
							var item = data[j];

							item.title = base.title;
							//prevClose 需要转换
							item.prevClose = parseFloat(base.prevClose);
							item.prevCloseTime = base.prevCloseTime;
							//open 需要转换
							item.open = parseFloat(base.open);
							item.openTime = base.openTime;
							item.type = base.type;

							//item.diffPercent = Math.round((item['price'] - item.prevClose) *10000 / item.prevClose)/100 + '%';
							item.diffPercent = ((item['price'] - item.prevClose) * 100 / item.prevClose).toFixed(2) + '%';


							if (item['price'] > item.prevClose) {
								item.trend = 'up';
								item.diffPercent = '+' + item.diffPercent;
								item.diff = '+' + item.diff;
							} else if (item['price'] < item.prevClose) {
								item.trend = 'down';
							} else {
								item.trend = '';
							}

							var fixLength = base['numberFormat'].length - 2;
							item.price = item.price.toFixed(fixLength);

							//
							results.push(item);
							//结束当前循环
							break;
						}
					}
				}
			
				if($(".markets.table tbody tr").length > 0 ){
					refreshData(results);
				}
				else{
					var html = template("asset_list_item",{"list":results});
					$(".markets.table tbody").append(html);
				}
				
			}
		});
	}

	function refreshData(results){
		for(var i=0,symbol;i<results.length;i++){
			symbol = results[i].symbol;
			$(".markets.table tbody tr[data-symbol='" + symbol + "'] .price span").html(results[i].price);
			$(".markets.table tbody tr[data-symbol='" + symbol + "'] .diffPercent span").html(results[i].diffPercent);
			$(".markets.table tbody tr[data-symbol='" + symbol + "'] .diffPercent span").attr("class","value " + results[i].trend);
		}
	}
	
	function getHash(){
		return window.location.hash ? window.location.hash.substring(1) : "forex";
	}
	
 }(Zepto));
 

	 
	
