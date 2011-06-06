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
                required: 'Enter your email address.',
                email: 'Check your email address for errors.'
            },
            li_password: {
                required: 'Enter your password.'
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
                        value: item.school,
                        id: item.id
                    };
                }));
                
            });
        },
        select: function (event, ui) {
            $('#su_school').value = ui.item.value;
            $('#su_school_id').value = ui.item.id;
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

// Should be run on #su_school on blur.
// Reverts #su_school to the school name represented by #su_school_id if available
function force_school() {
    var id = $('#su_school_id').val();
    alert('blur');
    
    // If id is not empty (some school already selected), replace the textbox value with
    // the correct name from the server (to avoid user confusion). Otherwise, clear the
    // autocomplete.
    if (id != '') {
        $.get('/login/get_school_by_id', {
            "id": id
        }, function(data) {
            alert('gotten response with id=' + id);
            if (data != 'error') {
                $('#su_school').val(data);
            }
        });
    } else {
        $('#su_school').val('');
    }
}