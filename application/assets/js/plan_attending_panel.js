$(function() {
    initialize_plan_attending_panel();
});

function initialize_plan_attending_panel() {
    // Make it draggable (with a handle).
    $('#plan_attending_panel').draggable({
        handle: '.title_bar'
    });
    
    // Closing click handler
    $('#cancel_friends_panel').click(function () {
        $('#plan_attending_panel').hide('fast');
    });
}

function populate_plan_attending_panel() {
    $.get('/home/attending_list', {
        plan_id : $('#view_attendees').attr('plan_id')
    }, function(data){ 
        $('.attending_list').html(data);
        $('#plan_attending_panel').show('fast');
            
        // Add following click handler
        $('.attending_list .add_following').confirmDiv(function(clicked_elem) {
            $.get('/dashboard/add_user_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function (data) {
                populate_plan_attending_panel();
            });
        });
    });
}