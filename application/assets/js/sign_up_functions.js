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
        // Submit and redirect
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
        // Custom error messages
        messages: {
            li_email: {
                required: 'Enter your email address.',
                email: 'Check your email address for errors.'
            },
            li_password: {
                required: 'Enter your password.'
            }
        },
        showErrors: function(errorMap, errorList) {
            // Adapted from http://stackoverflow.com/questions/4342950/jquery-validate-plugin-display-one-error-at-a-time-with-css/4343177#4343177
            // Displays one error at a time and changes the invalid input's class
            
            // Remove all error classes.
            $("#log_in").find("input").each(function() {
                $(this).removeClass("highlight_error");
            });
            
            // Add the error class to the first invalid field.
            $("#li_error").html("");
            if(errorList.length) {
                $("#li_error").html(errorList[0]['message']);
                $(errorList[0]['element']).addClass("highlight_error");
            }
        }
    });
    
    $.get('/login/test_email', function(data) {
        alert('returned: ' + data);
    });
    
    // Initialize the sign up Validator instance.
    $('#sign_up').validate({
        rules: {
            su_email_1: {
                required: true,
                email: true,
                remote: {
                    url: '/login/check_email',
                    type: 'get',
                    data: {
                        email: function() {
                            return $('#su_email_1').val();
                        }
                    }
                }
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
            su_school_id: {
                required: true
            },
            su_sex: {
                required: true
            },
            su_month: {
                required: true
            },
            su_day: {
                required: true
            },
            su_year: {
                required: true
            },
            su_grad_year: {
                required: true,
                max: get_year() + 6
            }
        },
        // Submit and redirect
        submitHandler: function(form) {
            // Send the form information to the try_sign_up function.
            alert($('#sign_up').serialize());
            $.get('/login/try_sign_up', $('#sign_up').serialize(), function(data) {
                // Redirect or display an error.
                if (data != 'error')  {
                    window.location.href = data;
                } else {
                    alert('try again');
                }
            });
        },
        // Custom error messages
        messages: {
            su_email_1: {
                required: 'Enter your email address.',
                email: 'Your email address must be valid.',
                remote: 'An account with that username already exists.'
            },
            su_email_2: {
                required: 'Re-enter you remail address.',
                equalTo: 'Your email addresses must match.'
            },
            su_password: {
                required: 'Enter a password.',
                rangelength: 'Your password must be between 8 and 20 characters.'
            },
            su_first_name: {
                required: 'Enter your first name.',
                rangelength: 'Your first name must be between 2 and 20 characters.'
            },
            su_last_name: {
                required: 'Enter your last name.',
                rangelength: 'Your last name must be between 2 and 20 characters.'
            },
            su_school: {
                required: 'Choose your school.'
            },
            su_school_id: {
                required: 'Choose your school.'
            },
            su_sex: {
                required: 'Choose your sex.'
            },
            su_month: {
                required: 'Choose your month of birth.'
            },
            su_day: {
                required: 'Choose your day of birth.'
            },
            su_year: {
                required: 'Choose your year of birth'
            },
            su_grad_year: {
                required: 'Enter your graduation year',
                max: "Your graduation year is too far away."
            }
        },
        showErrors: function(errorMap, errorList) {
            // Adapted from http://stackoverflow.com/questions/4342950/jquery-validate-plugin-display-one-error-at-a-time-with-css/4343177#4343177
            // Displays one error at a time and changes the invalid input's class
            
            // Remove all error classes.
            $("#sign_up").find("input, select").each(function() {
                $(this).removeClass("highlight_error");
            });
            
            // Add the error class to the first invalid field.
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
        // Get info from the server.
        source: function (request, response) {
            $.get('/login/search_schools', {
                needle: request.term
            }, function (data) {
                
                // Convert each item in the JSON from the server to the required JSON
                // form for the autocomplete and pass the result through the response
                // handler.
                data = $.parseJSON(data);
                response($.map(data, function (item) {
                    return {
                        label: item.school + ' (' + item.city + ')', 
                        value: item.school,
                        id: item.id
                    };
                }));
                
            });
        },
        // When an item is selected, update the school text as well as the hidden school
        // id field.
        select: function (event, ui) {
            $('#su_school').val(ui.item.value);
            $('#su_school_id').val(ui.item.id);
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

// Should be run on #su_school onblur.
// Ensures only a valid school can be submitted.
function force_school() {
    // Get the school id stored in the hidden field.
    var id = $('#su_school_id').val();
    
    if (id == '') {
        // If id is empty, clear the school box.
        $('#su_school').val('');
        
    } else {
        // A school was previously selected, so repopulate the school box with that
        // name (pulled from the server) This should make it clear to the user that
        // only a chosen school can be submitted.
        $.get('/login/get_school_by_id', {
            "id": id
        }, function(data) {
            if (data != 'error') {
                $('#su_school').val(data);
            }
        });
    }
}