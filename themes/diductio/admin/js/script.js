var $ = jQuery;
$(document).ready(function () {
    $('#statistic-recount').click(function () {
        statisticRecount(0);
    });
});

function statisticRecount(start) {
    $('#statistic-recount').text('Подождите...');
    var data = {
        action: 'stat_recount',
        start: start
    };

    $.post(ajax_path.url, data, function(response) {
        if(response.status == 'working') {
            $("#recount-precent").css('display','inline-block');
            $("#recount-precent").show();
            $("#recount-precent").text(response.percent + " %");
            statisticRecount(response.start);
        }
        if(response.status == 'done') {
            $("#recount-precent").slideUp(1500);
            $('#statistic-recount').text('Обновить');
        }
    },'json');
}