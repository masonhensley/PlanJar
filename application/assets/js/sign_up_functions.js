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
        showErrors: function(errorMap, errorList) {
            // Adapted from http://stackoverflow.com/questions/4342950/jquery-validate-plugin-display-one-error-at-a-time-with-css/4343177#4343177
            $("#log_in").find("input").each(function() {
                $(this).removeClass("highlight_error");
            });
            
            $("#li_error").html("");
            if(errorList.length) {
                $("#li_error").html(errorList[0]['message']);
                $(errorList[0]['element']).addClass("highlight_error");
            }
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
                required: true
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
            su_sex: {
                nna: 'true'
            },
            su_month: {
                nna: 'true'
            },
            su_year: {
                nna: 'true'
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
                alert(data);
            //                // Redirect or display the error.
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
                required: 'School is required.'
            },
            // Note that validating #su_sex isn't needed.
            su_grad_year: {
                required: 'Graduation year is required.',
                max: "Sorry, you can't graduate more than 6 years from now."
            }
        },
        showErrors: function(errorMap, errorList) {
            // Adapted from http://stackoverflow.com/questions/4342950/jquery-validate-plugin-display-one-error-at-a-time-with-css/4343177#4343177
            $("#sign_up").find("input").each(function() {
                $(this).removeClass("highlight_error");
            });
            
            $("#su_error").html("");
            if(errorList.length) {
                $("#su_error").html(errorList[0]['message']);
                $(errorList[0]['element']).addClass("highlight_error");
            }
        }
    });
    
    // Initialize the autocomplete instance.
    $('#su_school').autocomplete({
        minLength: 2,
        source: function (request, response) {
            $.get('/login/search_schools', {
                needle: request.term
            }, function (data) {
                
                // Convert each item in the input JSON to the required JSON form for the autocomplete
                // and pass the result through the response handler.
                data = $.parseJSON(data);
                response($.map(data, function (item) {
                    return {
                        label: item.school + ' (' + item.city + ')', 
                        value: item.school
                    };
                }));
                
            });
        },
        select: function (event, ui) {
            $('#su_school').value = ui.item.value;
        }
    })
    
// End of ready function.
});

// Returns the current year.
function get_year()
{
    var d = new Date();
    return d.getFullYear();
}

// Add a neq method for Validator.
$.validator.addMethod('nna',
    function (value, element) {
        return value != 'n/a';
    },
    element.label + " must not be blank.");