$(function() {
    initialize_view_friend_plan_modal();
});

function initialize_view_friend_plan_modal()
{

    $('.view_friends_plans').click(function(){
                
        $('#friends_plans_panel').show('fast');
                
        // Make it draggable (with a handle).
        $('#friends_plans_panel').draggable({
            handle: '.title_bar'
        });
    
        // Closing click handler
        $('#cancel_friends_panel').click(function () {
            $('#friends_plans_panel').hide('fast');
        });
                
        $('.friend_tab').click(function(){
            var friend_id = $(this).attr('user_id');
            load_friend_plans(friend_id);
        });
        
    });
}

function load_friend_plans(friend_id)
{
    alert(friend_id);
}