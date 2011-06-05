// Run when then DOM is loaded
$(document).ready(function() {
    alert('hi');
    
    // Initialize the sign up validity instance.
    $('#sign_up').validate({
        submitHandler: function(form) {
            $.get('/login/try_sign_up', $('#sign_up').serialize(), function(data) {
                alert('return: ' + data);
            })
        }
    });
    
    $('#log_in').validate({
        submitHandler: function(form) {
            $.get('/login/try_log_in', $('#log_in').serialize(), function(data) {
                alert('return: ' + data);
            })
        }
    });
});
    




    ///**--------------------------
    ////* Validate Date Field script- By JavaScriptKit.com
    ////* For this script and 100s more, visit http://www.javascriptkit.com
    ////* This notice must stay intact for usage
    //---------------------------**/
    //
    //function checkdate(input){
    //    var validformat=/^\d{2}\/\d{2}\/\d{4}$/ //Basic check for format validity
    //    var returnval=false
    //    if (!validformat.test(input.value))
    //        alert("Invalid Date Format. Please correct and submit again.")
    //    else{ //Detailed check for valid date ranges
    //        var monthfield=input.value.split("/")[0]
    //        var dayfield=input.value.split("/")[1]
    //        var yearfield=input.value.split("/")[2]
    //        var dayobj = new Date(yearfield, monthfield-1, dayfield)
    //        if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
    //            alert("Invalid Day, Month, or Year range detected. Please correct and submit again.")
    //        else
    //            returnval=true
    //    }
    //    if (returnval==false) input.select()
    //    return returnval
    //}
    //
    //returns the current year. used for login.
//    function getYear()
//    {
//        var d = new Date();
//        return d.getFullYear();
//    }
//
//    function try_sign_up() {
//    }
//