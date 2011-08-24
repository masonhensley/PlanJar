$(function() {
    initialize_group_list_panel();
});

function initialize_group_list_panel(){
    // Make it draggable (with a handle).
    $('#group_member_panel').draggable({
        handle: '.title_bar'
    });
    
    // Closing click handler
    $('#cancel_group_member_panel').click(function () {
        $('#group_member_panel').hide('fast');
    });
}