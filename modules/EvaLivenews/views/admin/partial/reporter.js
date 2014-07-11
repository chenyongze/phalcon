$.blockUI.defaults.message = '<i class="icon-spinner icon-spin"></i> Please Wait';
var updateCreateTime = function(){
    var day = $('input[name=day]').val();
    var time = $('input[name=time]').val();
    time = time.length == 7 ? '0' + time : time;
    var timestamp = moment(day + ' ' + time).format('X');
    $('input[name=createdAt]').val(timestamp);
}
$('input[name=day], input[name=time]').on('change', updateCreateTime);
$('input[name=day]').on('focus', function(){
    var day = $(this);
    if(day.val() == '') {
        day.val(moment().format('YYYY-MM-DD'));
    }
});
$('input[name=time]').on('focus', function(){
    var time = $(this);
    if(time.val() == '') {
        var timestamp = parseInt(moment().format('X'));
        time.val(moment.unix(timestamp + (60 - timestamp % 60)).format('HH:mm:ss'));
    }
});

var slider = $( "#importance-slider" );
slider.css('width', slider.parent().width()).css('margin-top', '10px').slider({
    value:1,
    range: "min",
    min: 1,
    max: 5,
    step: 1,
    slide: function( event, ui ) {
        var val = parseInt(ui.value);
        $(slider.attr('data-slider-for')).val(val);
    }
});


var loadLivenews = function(){
    $("#embed-livenews").load('/admin/livenews/news/embed', function(){
        var newsId = $('input[name=id]').val();
        newsId = newsId == '' ? 0 : parseInt(newsId);
        var newsArea = $(this);
        newsArea.find('div[data-news-id=' + newsId + ']').addClass('well').css("margin", 0);
        newsArea.slimScroll({
            height: $(window).height() - 150,
            alwaysVisible : true
        });
        newsArea.find('time').each(function(){
            var time = $(this);
            time.html(moment(time.attr("datetime") * 1000).fromNow());
        });
    });
}
loadLivenews();


$(document).on('click', ".remove-handler", function(e){
    if(!confirm('This news will be deleted, are you sure?')) {
        return false;
    }
    var handler = $(this);
    $.ajax({
        url : handler.attr('href'),
        type : 'DELETE',
        success : function(response){
            handler.closest('.profile-activity').remove();
        },
        error : function() {

        }
    });
    e.stopPropagation();
    return false;
});
