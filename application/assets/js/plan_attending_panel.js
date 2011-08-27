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
    }).complete(function(){ // when the awaiting list is fetched, get the attendees and assign follow click handler
        $.get('/home/attending_list', {
            plan_id : $('#view_attendees').attr('plan_id')
        }, function(data){
            $('.attending_list').html(data);
            $('#plan_attending_panel').show('fast');
            
            // click handler for attending button
            $('.attending_button').click(function(){
                if(!$(this).hasClass('guest_list_button_selected'))
                {
                    $('.guest_list_button_selected').removeClass('guest_list_button_selected');
                    $(this).addClass('guest_list_button_selected');
                    $('#awaiting_reply').hide();
                    $('#attending_modal_content').show();
                }
            });
        
            // click handler for 'not responded''
            $('.awaiting_button').click(function(){
                if(!$(this).hasClass('guest_list_button_selected'))
                {
                    $('.guest_list_button_selected').removeClass('guest_list_button_selected');
                    $(this).addClass('guest_list_button_selected');
                    $('#attending_modal_content').hide();
                    $('#awaiting_reply').show();
                }
            
            });
        
        }).complete(function(){
            following_click_handler(); // assign click handler to 'follow' buttons
        });
    });
    
    
}

function following_click_handler(){
    // Add following click handler
    $('.user_entry .add_following').confirmDiv(function(clicked_elem) {
        $.get('/dashboard/add_user_following', {
            following_id: $('.user_entry').attr('user_id')
        }, function () {
            populate_plan_attending_panel();
        });
    });
}