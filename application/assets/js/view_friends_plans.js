$(function() {
    initialize_view_friend_plan_modal();
});

// Initial setup for the view friend's plans modal
function initialize_view_friend_plan_modal()
{
    // Opening click handler
    $('.view_friends_plans').click(function(){
        // Show the modal
        $('#friends_plans_panel').show('fast');
    });
    
    // Closing click handler
    $('#cancel_friends_panel').click(function () {
        $('#friends_plans_panel').hide('fast');
    });
    
    // Friend tab click handler
    $('.friend_tab').click(function(){
        var friend_id = $(this).attr('user_id');
        load_friend_plans(friend_id);
    });
    
    // Draggable (with a handle).
    $('#friends_plans_panel').draggable({
        handle: '.title_bar'
    });
}

// Loads, displays, and sets up the plan tabs
function load_friend_plans(friend_id)
{
    $.get('/home/load_friend_plans', {
        'friend_id' : friend_id
    },
    function(data){
        // Hide the friend list
        $('.friend_modal_content').hide('slide', {
            direction: 'up'
        }, 'fast', function () {
            // Replace the HTML for the plans div
            $('.friend_plan_content').html(data);
        
            // Show the plans div
            $('.friend_plan_content').show(
                'slide', {
                    direction: 'up'
                }, 'fast');
        });
        
        // Back button click handler      
        $('.friend_plan_back_button').click(function(){
            // Hide the plans div
            $('.friend_plan_content').hide(
                'slide', {
                    direction: 'down'
                }, 'fast', function(){
                    // Show the friends div
                    $('.friend_modal_content').show('slide', {
                        direction: 'up'
                    }, 'fast');
                });
        });
        
        // Plan click handler
        $('.friend_plan_content').click(function () {
            if (!$(this).hasClass('selected_friend_plan')) {
                // Clear all controlls
                deselect_all_controlls();
                
                // Select this plan
                $(this).addClass('selected_friend_plan');
                
                // Display the info box
                display_info();
            }
        });
    });
}