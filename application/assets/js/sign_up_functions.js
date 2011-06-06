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
                if (data != 'error')  {
                    window.location.href = data;
                } else {
                    alert(data);
                }
            });
        },
        messages: {
            li_email: {
                required: 'Email address is required.',
                email: 'Email address must be valid.'
            },
            li_password: {
                required: 'Password is required.'
            }
        },
        errorLabelContainer: '#li_error_list',
        wrapper: 'li',
        showErrors: function(error_map, error_list) {
            if(error_list.length) {
                $("#li_error_list").html(error_list[0]['message']);
                $(errorList[0]['element']).addClass("error");
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
                    required: true,
                    equalTo: '#su_email_1'
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
                alert($('#sign_up').serialize());
                $.get('/login/try_sign_up', $('#sign_up').serialize(), function(data) {
                    // Redirect or display the error.
                    alert(data);
                    //                if (data != 'error')  {
                    //                    window.location.href = data;
                    //                } else {
                    //                    alert(data);
                    //                }
                });
            },
            messages: {
                su_email_1: {
                    required: 'Email address is required.',
                    email: 'Email address must be valid.'
                },
                su_email_2: {
                    required: 'Re-enter you remail address.',
                    equalTo: 'Email addresses must match.'
                },
                su_password: {
                    required: 'Password is required.',
                    rangelength: 'Password must be between 8 and 20 characters.'
                
                },
                su_first_name: {
                    required: 'First name is required.',
                    rangelength: 'First name must be between 2 and 20 characters.'
                },
                su_last_name: {
                    required: 'Last name is required.',
                    rangelength: 'Last name must be between 2 and 20 characters.'
                },
                su_school: {
                    required: 'School is required.',
                    maxlength: 60
                },
                // Note that validating #su_sex isn't needed.
                su_month: {
               
                },
                su_grad_year: {
                    required: 'Graduation year is required.',
                    max: "Sorry, you can't graduate more than 6 years from now."
                }
            },
            errorLabelContainer: '#su_error_list',
            wrapper: 'li'
        });
    });

    // Returns the current year.
    function get_year()
    {
        var d = new Date();
        return d.getFullYear();
    }