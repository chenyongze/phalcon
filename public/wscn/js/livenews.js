/**
 * Created by Sun on 14-7-24.
 */
;(function(){
    if ($('.page-livenews').length) {

        var $content = $('#news-list > .content');
        var margin = 40 - $content.height();
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
                        'margin-top': '+=40'
                    }, 300);
                }
            } else if (action === 'down') {
                if (mTop == margin) {
                    $content.stop().animate({
                        'margin-top': 0
                    }, 300);
                } else {
                    $content.stop().animate({
                        'margin-top': '-=40'
                    }, 300);
                }
            }
        });
    }
})();