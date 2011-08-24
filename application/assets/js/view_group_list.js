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

function populate_group_member_panel(){
    $.get('/home/group_member_list', {
        group_id : $('.selected_group').attr('group_id')
    }, function(data){
        $('.member_list').html(data);
        $('#group_member_panel').show('fast');
        
        // Add following click handler
        $('.member_list .add_following').confirmDiv(function(clicked_elem) {
            $.get('/dashboard/add_user_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function () {
                populate_plan_attending_panel();
            });
        });
        
    });
}