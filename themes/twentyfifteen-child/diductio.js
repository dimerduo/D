var $ = jQuery;

$(document).ready(function(){
	$('input.accordion-checkbox').click(function(event){
		lessonElemChecked(event.target)
	});
});

/* (11) Отправка статистических данных для записи в БД */
function lessonElemChecked (obj) {
	var checked_elements = new Array()
	var has_checked = false;
	$('input.accordion-checkbox:checked').each(function(){
		has_checked = true;
		checked_elements.push($(this).data('accordion-count'));
	});	
	if(!has_checked) {
		checked_elements[0] = 0;
	}

	var post_id = $(obj).data('post-id');
	var lessons_count = $('input.accordion-checkbox').length;
	$(obj).attr('disabled','disabled');
	$.ajax({
	  type: 'POST',
	  url: diductioObject.child_theme_url + '/requests.php',
	  data: {'post_id': post_id,'checked_elements':checked_elements, 'lessons_count':lessons_count},
	  success: function(data){}
	});	  	
}

/* (11) Отправка статистических данных для записи в БД end*/
