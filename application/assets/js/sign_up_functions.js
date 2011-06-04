// Run when then DOM is loaded
$(function() {
    // Initialize the sign up validity instance.
    alert('validity is next');
    $("sign_up").validity(function() {
        $('#su_email_1').require();
        $('#su_email_2').require();
    });
    $.validity.setup({outputMode: 'modal'});
});



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
