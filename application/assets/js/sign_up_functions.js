// Run when then DOM is loaded
$(document).ready(function() {
    
    //    $.get('/user/check_email',
    //    {
    //        email: 'hello@123.com'
    //    },
    //    function (data) {
    //        alert(data);
    //    });

    // Initialize the sign up validate instance.
    $("#sign_up").validate({
        rules: {
            su_email_1: {
                required: true,
                email: true
            },
            su_email_2: {
                required: true,
                email:true,
                equalTo: su_email_1.value
            },
            su_password: {
                required: true,
                rangeLength: [6, 20]
            },
            su_first_name: {
                required:true,
                rangeLength: [2, 20]
            },
            su_last_name: {
                required: true,
                rangeLength: [2, 25]
            },
            su_school: {
                required: true
            },
            su_birthday: {
                required: true,
                date: true
            },
            su_grad_year: {
                required:true
            }
        },
        submitHandler: function(form) {
            if (!checkdate(form.birthday.text)) {
                alert('bad date');
                return;
            }
            alert('success');
        },
        invalidHandler: function(form, validator) {
            alert('invalid');
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element)
        }
    });
    
    // Initialize the login validate instance.
    //    $("#login").validate({
    //        rules: {
    //            email: {
    //                required: true,
    //                email: true
    //            },
    //            password: {
    //                required: true,
    //                password: true
    //            }
    //        },
    //        submitHandler: function(form) {
    //        },
    //        invalidHandler: function(form, validator) {
    //        }
    //    });
    
    /**--------------------------
//* Validate Date Field script- By JavaScriptKit.com
//* For this script and 100s more, visit http://www.javascriptkit.com
//* This notice must stay intact for usage
---------------------------**/

    function checkdate(input){
        var validformat=/^\d{2}\/\d{2}\/\d{4}$/ //Basic check for format validity
        var returnval=false
        if (!validformat.test(input.value))
            alert("Invalid Date Format. Please correct and submit again.")
        else{ //Detailed check for valid date ranges
            var monthfield=input.value.split("/")[0]
            var dayfield=input.value.split("/")[1]
            var yearfield=input.value.split("/")[2]
            var dayobj = new Date(yearfield, monthfield-1, dayfield)
            if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
                alert("Invalid Day, Month, or Year range detected. Please correct and submit again.")
            else
                returnval=true
        }
        if (returnval==false) input.select()
        return returnval
    }
    
    //returns the current year. used for login.
    function getYear()
    {
        var d = new Date();
        return d.getFullYear();
    }
});