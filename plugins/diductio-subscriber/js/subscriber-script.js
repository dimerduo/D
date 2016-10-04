$(document).ready(function(){
 	$(".add-subscriber").click(function(){
 		sbcrb(this, 'author');
 	});

 	$(".tag-subscribe").click(function(){
 		sbcrb(this, 'tag');
 	});

 	$(".category-subscribe").click(function(){
 		sbcrb(this, 'category');
 	});
});

function sbcrb(obj, type)
{
	var id = $(obj).attr('id').split("-")[1];
	$(obj).text('Подождите...');
	$(obj).addClass('sbscr-loading')
	
	var data = new Object();
	switch(type) {
	  case 'author': 
	  	data.action = 'subscribe';
	  	data.user_id = id; 
	    break;
	  case 'tag':
	    data.action = 'tag_subscribe';
	    data.tag_id = id;
	    break;
	  case 'category':  
	    data.action = 'сategory_subscribe';
	    data.cat_id = id;
	    break;
	}

	$.post(didAjax.url, data, function(response) {
		if(response.status == 'ok') {
			$(obj).removeClass('sbscr-loading');
			$(obj).removeClass('link-style-3');
			$(obj).addClass('sbscr-message sbsr-success').text(response.message);
		}
	},'json');	
}