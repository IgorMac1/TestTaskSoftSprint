$(function() {

    $('form#ajax_form').submit(function( event ) {
        let formData = $(this).serializeArray();
        event.preventDefault();

        addUser(formData);
    });

    function addUser(formData) {
        let userData = {};
        $.each(formData, function(i, field) {
            userData[field.name] = field.value;
        });
        console.log(userData);

        $.ajax({
            url: "/api/users",
            type: 'POST',
            data: userData,
            success: function(result) {
                console.log('success');
                console.log(result);
            },
            error: function (result) {
                console.log('error');
                console.log(result);
            }
        });
    }

});