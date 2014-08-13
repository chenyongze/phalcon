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

        var $group = $('.page-livenews .checkbox-group');
        var $moreContent = $group.find('.more-content');
        function createTag(name, text) {
            return '<span class="tag" name="' + name + '"><i class="fa fa-times-circle"></i>' + text + '</span>';
        }

        $group.on('click', '.more', function(e){
            $moreContent.slideToggle();
            $(this).toggleClass('active');
        });

        $group.on('click', '.custom-checkbox', function(e){
            var $this = $(this);
            var $input = $this.find('[type=checkbox]');
            var input = $input[0];
            var name = input.name;
            //
            var $sameInput = $group.find('[type=checkbox][name=' + name + ']');
            var length = $sameInput.length;
            while(length --) {
                $sameInput[length].checked = input.checked;
            }
            //
            if (input.checked) {
                var text = $this.find('.text').text();
                var tag = createTag(name, text);
                $group.append(tag);
            } else {
                var $tag = $group.find('.tag[name=' + name + ']');
                $tag.remove();
            }

        });
        $group.on('click', '.tag', function(e){
            var $this = $(this);
            var name = $this.attr('name');
            var $checkbox = $group.find('[type=checkbox][name=' + name + ']');
            var length = $checkbox.length;
            while(length --) {
                $checkbox[length].checked = false;
            }
            $this.remove();
        });
    }
})();