// Run when then DOM is loaded
$(document).ready(function() {

    // Initialize the validate plugins.
    $("#sign_up").validate({
        rules: {
            email_1: {
                required: true,
                email: true
            },
            email_2: {
                required: true,
                email:true,
                equalTo: email_1
            },
            password: {
                required: true,
                password: true
            },
            first_name: {
                required:true
            },
            last_name: {
                required: true
            }
        },
        submitHandler: function(form) {
        },
        invalidHandler: function(form, validator) {
        }
    });
    
    $("#login").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                password: true
            }
        },
        submitHandler: function(form) {
        },
        invalidHandler: function(form, validator) {
        }
    });
});