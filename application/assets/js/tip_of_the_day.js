$(function() {
    // Open the tip of the day if necessary
    $.get('/home/get_show_tip', function(data) {
        if (data == 'show') {
            $('#tip_of_the_day').show('blind', {}, 'fast');
        } 
    });
    
    // Closing click handler
    $('#close_tip').click(function() {
        // Hide the tip
        $('#tip_of_the_day').hide('blind', {}, 'fast');
        
        // Register with the server
        $.get('/home/set_show_tip', {
            value: 1
        });
    });
});