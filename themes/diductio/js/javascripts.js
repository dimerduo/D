var $ = jQuery;

var diductioClass = function () {
    var self = this;

    this.sendAjax = function (action, data, callback) {
        $.ajax({
            type: 'POST',
            url: diductioObject.ajax_path,
            data: {
                action: action,
                data: data
            },
            success: function (response) {
                callback(response);
            },
            dataType: 'json'
        });
    };
};
var diductio = new diductioClass();

$(document).ready(function () {
    $('input.accordion-checkbox').click(function (event) {
        lessonElemChecked(event.target)
    });

    $('div.more-statistic').click(function (event) {
        $("#statistic").append("<div class='loading'>Загрузка ...</div>");
        if($('.users-post-progress-container').length > 0) {
           $('.users-post-progress-container').slideUp(500, function () {
               moreStatistic(event)
           });
        } else {
            moreStatistic(event);
        }
    });

    $('#display-more-users').click( function(){
        $(this).parent().find('i').toggleClass('glyphicon-plus glyphicon-minus');
        $(this).toggleText('Развернуть', 'Свернуть');
        $('.rest-users').slideToggle(600);
    });

    $('.remove-user').click(function(event){
        removeUser(event.target);
    });
});

/* (11) Отправка статистических данных для записи в БД */
function lessonElemChecked(obj) {
    var checked_elements = new Array()
    var has_checked = false;
    $('input.accordion-checkbox:checked').each(function () {
        has_checked = true;
        checked_elements.push($(this).data('accordion-count'));
    });
    if (!has_checked) {
        checked_elements[0] = 0;
    }

    var post_id = $(obj).data('post-id');
    var lessons_count = $('input.accordion-checkbox').length;
    $(obj).attr('disabled', 'disabled');
    $.ajax({
        type: 'POST',
        url: diductioObject.child_theme_url + '/requests.php',
        data: {'post_id': post_id, 'checked_elements': checked_elements, 'lessons_count': lessons_count},
        success: function (data) {

        }
    });
}
function moreStatistic(event) {
    var showMore = new showMoreClass();
    var post_id = event.target.getAttribute('data-postid');
    var user_group = event.target.getAttribute('data-user-group');
    if (post_id) {
        var data = {
            action: 'show_more_statistic',
            post_id: post_id,
            user_group: user_group
        };
        $.ajax({
            type: 'POST',
            url: diductioObject.ajax_path,
            data: data,
            success: function (result) {
                if (result.status == 'ok') {
                    $('div.loading').remove();
                    if(showMore.isBlockExist()) {
                        showMore.destroy();
                    }
                    showMore.init();
                    $.each(result.data, function (index, value) {
                        showMore.buildStatRow(value);
                    });
                    showMore.showBlock();
                }
            },
            dataType: 'json'
        });
    }
}
/* (11) Отправка статистических данных для записи в БД end*/

var showMoreClass = function(){
     /**
     * Self reference
     */
     var self = this;

     /**
     * Users progress main container
     * @type {string}
     */
     this.all_stat_container;

    this.init = function()
    {
        self.all_stat_container = $('<div/>', { class: 'users-post-progress-container'});
        $("#statistic").append(self.all_stat_container);
    }

    /**
     * Render all more statistic block
     * @param json object
     */
    this.buildStatRow = function (value)
    {
        var user_stat_container = $('<div/>', {class : 'post-users-stat'});
        var complete_class = value.progress == 100 ? 'progress-bar-success' : '';
        var progress =
            $('<div/>', { class: 'progress' })
                .append( $('<div />', {
                        class: 'progress-bar ' + complete_class,
                        role: 'progressbar',
                        'aria-valuenow': value.progress,
                        'aria-valuemin': 0,
                        'aria-valuemax': 100,
                        style: 'width:' + value.progress + '%;',
                        text: value.progress + ' %'
                    }
                ));
        var stat_header = $('<header/>')
            .append($('<div/>')
                .append($('<a/>', { href: value.user_link })
                    .append(value.avatar)
                )
            )
            .append($('<div/>')
                .append($('<span/>')
                    .append($('<a/>',{ href: value.user_link, text: value.username}))
                )
            )

        self.all_stat_container.append(user_stat_container);
        user_stat_container.append(stat_header);
        user_stat_container.append(progress);
    }

    /**
     * Show users progress block by post
     */
    this.showBlock = function ()
    {
        $('.users-post-progress-container').slideDown(1000);
    }

    /**
     *  Hide users progress block by post
     */
    this.hideBlock = function ()
    {
        $('.users-post-progress-container').slideUp(1000);
    }

    /**
     * Destroy statisitc
     */
    this.destroy = function()
    {
        $('.users-post-progress-container').remove();
    }

    /**
     * Block exist
     */

    this.isBlockExist = function()
    {
        return ($('.users-post-progress-container').length > 0) ? true : false ;
    }

};

jQuery.fn.extend({
    toggleText: function(stateOne, stateTwo) {
        return this.each(function() {
            stateTwo = stateTwo || '';
            $(this).text() !== stateTwo && stateOne ? $(this).text(stateTwo)
                : $(this).text(stateOne);
        });
    }
});

function removeUser(obj)
{
    var user_id = $(obj).data('userid');
    var $checkbox = $(obj).parents('.accordion-content').find('.accordion-checkbox');
    var post_id = $checkbox.data('post-id');
    var accordion_element = $checkbox.data('accordion-count');
    var data  = {
        "user_id": user_id,
        "post_id": post_id,
        "accordion_element": accordion_element
    };
    diductio.sendAjax('removeLessonPartFromUser', data, function (response) {
        location.reload();
    });
}


