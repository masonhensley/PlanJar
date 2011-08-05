function followers_setup() {
    populate_followers_list();
}

function populate_followers_list() {
    
    
    $.get('/dashboard/get_followers', function (data) {
        $('#followers_list').html(data);
        
        // Click handler.
        $('#followers_list .add_following').confirmDiv(function (clicked_elem) {
            $.get('/dashboard/add_user_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function (data) {
                populate_followers_list();
                populate_following_list();
            });
        });
        
        // Make followers selectable
        $('#followers_list .user_entry').click(function() {
        
            if(!$(this).hasClass('selected_follower'))
            {
                //$('#friends_content .right').hide();
                $('.user_entry.selected_follower').removeClass('selected_follower');
                $(this).addClass('selected_follower');
                $.get('/dashboard/get_profile', {
                    user_id: $(this).attr('user_id')
                }, function (data) {
                    $('#friends_content .right').hide();
                    $('#friends_content .right').html(data);
                    $('#friends_content .right').show("slow");
                });
            }

            
        });
    });
}