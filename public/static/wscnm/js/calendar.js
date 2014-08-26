
$(function ($) {
	var CAL_REST_ITEMS_URL = "http://api.markets.wallstreetcn.com/v1/calendar.json?",IMPORTANCE_MAP = {"1":"低","2":"中","3":"高"};
	
	$.calendar = {
		dayCN : ["日","一","二","三","四","五","六"],
		parseDate: function(obj) {
			var dateStr = $(".datetime-label").attr("data-date").split("-");
			return Object.prototype.toString.call(obj) === '[object Date]' ? obj : new Date(dateStr[0],dateStr[1] - 1,dateStr[2]);
		},

		formatDate: function(date) {
			$(".datetime-label").attr("data-date",date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate());
			return "星期" + this.dayCN[date.getDay()] + "，" + date.getFullYear() + '年' + (date.getMonth() + 1) + '月' + date.getDate() + "日";
		}
	};

	
	$(".datetime-label").calendar({
		hide:function(evt,cal){
			//getData();
		},
		commit:function(){
			console.log("commit.............");
			getData();
		}
	});
	
	$('.cal-icon').on('click', function(){
        $(".datetime-label").calendar('show');
    });
	
	$(".datetime-label").html($.calendar.formatDate(new Date()));
	
	/**Init**/
	getData();
	
	/** Functions **/
	function getData(){
		var $ = Zepto,dateStart = $.calendar.parseDate(null);
		$(".fd .content,.fe .content,.sr .content,.bi .content,.vn .content").html("");
		$.ajax({
			url : CAL_REST_ITEMS_URL + 'start=' + moment(dateStart).format('YYYY-MM-DD') + '&end=' + moment(dateStart).add('days', 1).format('YYYY-MM-DD'),
			dataType : 'jsonp',
			success : function(response) {
				//console.log(response);
				createCalItems(response);
			}
		});
	 }
	 
	 function getTime(item){
		if(item.calendarType == "FD" || item.calendarType == "FE" 
			|| item.calendarType == "BI"){
			return item.localDateTime.split(" ")[1].substring(0,5);
		}
		else{
			return item.localDateTime.split(" ")[0].substring(5);
		}
	}
	 
	 function getColor(item){
		var className="";
		if(item.actual && item.actual != "NULL" && item.actual!="&nbsp;"){
			if(item.forecast){
				if(parseFloat(item.actual) > parseFloat(item.forecast)){
					className="red";
				}
				else if(parseFloat(item.actual) < parseFloat(item.forecast)){
					className="green";
				}
				else{
					className="";
				}
			}
		}
		return className;
	 }
	 
	 function createCalItems(response){
		var $ = Zepto,itemsObj = [],marks={},data = response.results;
		for(var i=0;i<data.length;i++){
			var item = data[i],time = getTime(item);
			item.actualValueColor = getColor(item);
			item.importanceColor = (item.importance+"") == "3" ? "red" : "";
			item.bg = (item.importance+"") == "3" ? "important-bg" : "normal-bg";
			item.importance = IMPORTANCE_MAP[item.importance + ""];
			item.forecast && (item.forecast = item.forecast.replace(/&nbsp;/ig,""));//fix html space bug
			item.forecast && (item.forecast = item.forecast ? item.forecast : "--");
			
			if(!marks[time]){
				var obj = {"time":time,localDateTime:item.localDateTime,list:[]};
				itemsObj.push(obj);
				marks[time] = obj.list;
			}
			marks[time].push(item);
		}
		console.log(itemsObj);
		instTpl($.extend(true, [], itemsObj),"FD","cal_value_list_tpl");
		instTpl($.extend(true, [], itemsObj),"FE","cal_nonvalue_list_tpl");
		instTpl($.extend(true, [], itemsObj),"BI","cal_nonvalue_list_tpl");
		instTpl($.extend(true, [], itemsObj),"VN","cal_nonvalue_list_tpl");
		instTpl($.extend(true, [], itemsObj),"SR","cal_value_list_tpl");
	 }
	 
	 function instTpl(itemsObj,calType,tplId){

		for(var i=itemsObj.length-1,itemObj;i>=0;i--){
			itemObj = itemsObj[i];
			for(var j=itemObj.list.length-1;j>=0;j--){
				if(itemObj.list[j].calendarType != calType){
					itemObj.list.splice(j,1);
				}
			}
			if(itemObj.list.length == 0){
				itemsObj.splice(i,1);
			}
		}
		
		for(var i=0;i<itemsObj.length;i++){
			var html = template(tplId,itemsObj[i]);
			$("." + calType.toLowerCase() + " .content").append(html);
		}
		
	 }
 
 }(Zepto));
 
 