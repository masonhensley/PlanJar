// Run when then DOM is loaded
$(document).ready(function() {
    
    // Initialize the log in Validator instance.
    $('#log_in').validate({
        rules: {
            li_email: {
                required: true,
                email: true
            },
            li_password: {
                required: true
            }
        },
        submitHandler: function(form) {
            // Send the form information to the try_login function.
            $.get('/login/try_log_in', $('#log_in').serialize(), function(data) {
                // Redirect or display the error.
                if (data == 'error')  {
                    alert(data);
                } else {
                    window.location.href = data;
                }
            });
        },
        messages: {
            email: 'Your email must be a valid email address.'
        }
    });
    
    // Initialize the sign up Validator instance.
    $('#sign_up').validate({
        rules: {
            su_email_1: {
                required: true,
                email: true
            },
            su_email_2: {
                required:true,
                equalto: '#su_email_2'
            },
            su_password: {
                required: true,
                rangelength: [8, 20]
            },
            su_first_name: {
                required: true,
                rangelength: [2, 20]
            },
            su_last_name: {
                required: true,
                rangelength: [2, 20]
            },
            su_school: {
                required: true,
                maxlength: 60
            },
            // Note that validating #su_sex isn't needed.
            su_birthday: {
                required: true,
                date:true
            },
            su_grad_year: {
                required: true,
                max: get_year() + 6
            }
        },
        submitHandler: function(form) {
            // Send the form information to the try_sign_up function.
            $.get('/login/try_sign_up', $('#sign_up').serialize(), function(data) {
                // Redirect or display the error.
                alert(data);
            //                if (data == 'error')  {
            //                    alert(data);
            //                } else {
            //                    window.location.href = data;
            //                }
            });
        }
    });
});

// Returns the current year.
function get_year()
{
    var d = new Date();
    return d.getFullYear();
}