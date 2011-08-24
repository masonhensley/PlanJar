$(function() {
    initialize_group_list_panel();
});

function initialize_group_list_panel(){
    // Make it draggable (with a handle).
    $('#plan_attending_panel').draggable({
        handle: '.title_bar'
    });
    
    // Closing click handler
    $('#cancel_attending_panel').click(function () {
        $('#plan_attending_panel').hide('fast');
    });
}