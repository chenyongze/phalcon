var WSCN_UTIL = {};

(function (util) {

	util.cookie = {
		getCookie : function (key) {

			if (document.cookie.length > 0) {
				var start = document.cookie.indexOf(key + "=");
				if (start != -1) {
					start = start + key.length + 1;
					end = document.cookie.indexOf(";", start);
					if (end == -1) {
						end = document.cookie.length;
					}
					return unescape(document.cookie.substring(start, end));
				}
			}
			return "";
		},

		setCookie : function (key, value, expiredays) {
			var expires = '';
			var date = new Date();
			if (expiredays) {
				date.setDate(date.getDate() + expiredays);
				expires = ';expires=' + date.toUTCString();
			} else {
				expires = ';expires=Fri, 31 Dec 9999 23:59:59 GMT';
			}
			document.cookie = encodeURIComponent(key) + "=" + encodeURIComponent(value) + expires;
		}
	};

	util.url = {
		parseQueryString : function (url) {
			url && (url = url.substr(url.indexOf("?") + 1)); 
			var result = {}, queryString = url || location.search.substring(1),
				re = /([^&=]+)=([^&]*)/g,m;
			while (m = re.exec(queryString)) { 
				result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]); 
			}
			return result;
		}
	};

	util.mobile = {
		detectOrientation : function (callback) {
			var supportOrientation = (typeof window.orientation == "number" && typeof window.onorientationchange == "object");

			var updateOrientation = function () {
				if (supportOrientation) {
					updateOrientation = function () {
						var orientation = window.orientation;
						switch (orientation) {
						case 90:
						case  - 90:
							orientation = "landscape";
							break;
						default:
							orientation = "portrait";
						}
						document.body.parentNode.setAttribute("class", orientation);
						callback && callback(orientation);
					};
				} else {
					updateOrientation = function () {
						var orientation = (window.innerWidth > window.innerHeight) ? "landscape" : "portrait";
						document.body.parentNode.setAttribute("class", orientation);
						callback && callback(orientation);
					};
				}
				updateOrientation();
			};

			var init = function () {
				updateOrientation();
				if (supportOrientation) {
					window.addEventListener("orientationchange", updateOrientation, false);
				} else {
					window.setInterval(updateOrientation, 5000);
				}
			};
			window.addEventListener("DOMContentLoaded", init, false);
		}

	};

})(WSCN_UTIL)
