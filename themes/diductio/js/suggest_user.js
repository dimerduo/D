var suggestToUserClass = function () {

    /**
     * @type {suggestToUser}
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

    this.sendAjax = function (url, data) {

        $.ajax({
            type: 'POST',
            url: url,
            data: {
                action: "suggestUsers",
                users: data
            },
            success: function (result) {
                console.log('test')
            },
            dataType: 'json'
        });
    };

    /**
     *
     */
    this.init = function(){
        // save

    };

    this.save = function () {
        var users = [];

        // collect data
        $(".suggested-user").each(function(){
            var user = {};

            user.id = $(this).data('user');
            user.alreadyHas = false;

            if ($(this).is(':checked')) {
                user.alreadyHas = true;
            }

            users.push(user);
        });


        // send ajax
        if(users) {
            self.sendAjax(diductioObject.ajax_path, users);
        }
    };
};

// initialize object
var suggestToUser = new suggestToUserClass();
suggestToUser.init();