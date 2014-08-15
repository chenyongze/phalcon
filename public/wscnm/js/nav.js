$(function ($) {
    $('.left-menu').length > 0 && $('.left-menu').panel({
        contentWrap: $('.content-wrapper,.toolbar,.footerbar'),
		position:"left",
		scrollMode:"hide",
		close:function(){
			$(".toolbar").css("-webkit-transform","");
		}
    });
	
    $('.menu-indicator').length > 0 && $('.menu-indicator').on('click', function (e) {
        $('.left-menu').panel('toggle', 'push');
    });
	
	$('.gotop').length > 0 && $('.gotop').gotop({
		position:{bottom:35,right:10}
	});
	
	$('.login-icon').length > 0 && $('.login-icon').gotop({
		position:{bottom:75,right:10}
	});
 }(Zepto));