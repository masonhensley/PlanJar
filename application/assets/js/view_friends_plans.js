$(function() {
    initialize_view_friend_plan_modal();
});

function initialize_view_friend_plan_modal()
{

    $('.view_friends_plans').click(function(){
        
        $.get('/home/show_friend_modal/', 
            function(){
                
                // Make it draggable (with a handle).
                $('#friends_plans_panel').draggable({
                    handle: '.title_bar'
                });
    
                // Closing click handler
                $('#cancel_plan').click(function () {
                    $('#friends_plans_panel').hide('fast');
                });
                
            });
    });
}