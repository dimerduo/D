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
            statisticRecount(response.start);
        }
        if(response.status == 'done') {
            $('#statistic-recount').text('Обновить');
        }
    },'json');
    console.log(data);
}