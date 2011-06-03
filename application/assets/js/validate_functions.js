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
                rangeLength: [6, 20]
            },
            first_name: {
                required:true
            },
            last_name: {
                required: true
            },
            school: {
                required: true
            },
            sex: {
                reguired: true
            }
        },
        submitHandler: function(form) {
            alert('success');
        },
        invalidHandler: function(form, validator) {
            showErrors();
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