$(function() {
    initialize_plan_attending_panel();
});

function initialize_plan_attending_panel() {
    // Make it draggable (with a handle).
    $('#plan_attending_panel').draggable({
        handle: '.title_bar'
    });
    
    // Closing click handler
    $('#cancel_attending_panel').click(function () {
        $('#plan_attending_panel').hide('fast');
    });
}

function populate_plan_attending_panel() {
    $.get('/home/awaiting_list', {
        plan_id : $('#view_attendees').attr('plan_id')
    }, function(data){
        $('#awaiting_list').html(data);
    });
    
    $.get('/home/attending_list', {
        plan_id : $('#view_attendees').attr('plan_id')
    }, function(data){
        $('.attending_list').html(data);
        $('#plan_attending_panel').show('fast');
            
        following_click_handler();
        
        $('.attending_button').click(function(){
            if(!$(this).hasClass('guest_list_button_selected'))
            {
                $('.guest_list_button_selected').removeClass('guest_list_button_selected');
                $(this).addClass('guest_list_button_selected');
                $('#awaiting_reply').hide();
                $('#attending_modal_content').show();
            }
        });
        
        $('.awaiting_button').click(function(){
            if(!$(this).hasClass('guest_list_button_selected'))
            {
                $('.guest_list_button_selected').removeClass('guest_list_button_selected');
                $(this).addClass('guest_list_button_selected');
                $('#attending_modal_content').hide();
                $('#awaiting_reply').show();
            }
            
        });
        
    });
}

function following_click_handler(){
    // Add following click handler
    $('.attending_list .add_following').confirmDiv(function(clicked_elem) {
        $.get('/dashboard/add_user_following', {
            following_id: clicked_elem.parent().attr('user_id')
        }, function () {
            populate_plan_attending_panel();
        });
    });
}