// Run when the tab is selected
function followers_setup() {
    populate_followers_list();
}

// Populates the list of followers (friends) and assigns the click events
function populate_followers_list() {
    $.get('/dashboard/get_followers', function (data) {
        $('#followers_list').html(data);
        
        // Click handler.
        $('#followers_list .add_following').confirmDiv(function (clicked_elem) {
            $.get('/dashboard/add_user_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function (data) {
                populate_followers_list();
            });
        });
        
        // Make followers selectable
        $('#followers_list .user_entry').click(function() {
            if(!$(this).hasClass('selected_follower'))
            {
                // setup spinner
                var friends_opts = spinner_options();
                var friends_target = document.getElementById('friends_spinner');
                var friends_spinner = new Spinner(friends_opts).spin(friends_target);
                
                $('.user_entry.selected_follower').removeClass('selected_follower');
                $(this).addClass('selected_follower');
                $.get('/dashboard/get_profile', {
                    user_id: $(this).attr('user_id')
                }, function (data) {
                    $('#friends_content .right').hide();
                    $('#friends_content .right').html(data);
                    $('#friends_content .right').show("fast");
                }).complete(function(){
                    friends_spinner.stop();
                });
            }
        });
    });
}