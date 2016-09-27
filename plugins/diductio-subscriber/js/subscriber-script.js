$(document).ready(function(){
 	$(".add-subscriber").click(function(){
 		sbcrb(this);
 	});
});

function sbcrb(obj)
{
	var id = $(obj).attr('id').split("-")[1];
	$(obj).text('Подождите...');
	$(obj).addClass('sbscr-loading')

	var data = {
		action: 'subscribe',
		user_id: id
	};
	$.post(didAjax.url, data, function(response) {
		if(response.status == 'ok') {
			$(obj).removeClass('sbscr-loading');
			$(obj).removeClass('link-style-3');
			$(obj).addClass('sbscr-message sbsr-success').text(response.message);
		}
	},'json');	
}