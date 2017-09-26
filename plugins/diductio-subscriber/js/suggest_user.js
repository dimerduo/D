var suggestToUserClass = function () {

    /**
     * 
     * @type {suggestToUserClass}
     */
    var self = this;

    /**
     *
     */
    this.addUser =  function () {

    };

    /**
     *
     */
    this.showModal = function () {

    };

    this.sendAjax = function (data, callback) {
        var url = diductioObject.ajax_path;
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (result) {
                callback(result);
            },
            dataType: 'json'
        });
    };

    /**
     *
     */
    this.init = function(){
        $('#save-subscribers').click(function(){
            self.save($(this));
        });
    };

    this.save = function (target) {
        var users = [];
        $(target).text('Подождите...');

        // collect data
        $(".suggested-user").each(function(){
            var user = {};
            user.id = $(this).data('user');
            user.wasChecked = $(this).data('haschecked') == 1;
            user.alreadyHas = $(this).is(':checked') == 1;
            users.push(user);
        });
        var data =  {
            action: "suggestUsers",
            users: users,
            postid: $('#postid').val()
        };

        // send ajax
        if(users) {
            self.sendAjax(data, function () {
                $('#suggestUser').modal('toggle');
                window.location.reload();
                $(target).text('Сохранить');
            });
        }
    };
};

// initialize object
var suggestToUser = new suggestToUserClass();
suggestToUser.init();