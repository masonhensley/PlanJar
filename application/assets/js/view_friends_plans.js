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
        $('#canel_friends_panel').click(function () {
            $('#friends_plans_panel').hide('fast');
        });
                
        
    });
}