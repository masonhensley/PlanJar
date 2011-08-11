$(function () {
    initialize_suggested_friends();
    initialize_follow_search();
});

// Run when the tab is selected
function following_setup() {
    populate_following_list();
    
}

// This sets up the suggested friends list
function initialize_suggested_friends()
{
    $('.suggested_friends').click(function() {
        if($(this).hasClass('suggested_active'))
        {
            // Already selected. Deselect and clear
            $(this).removeClass('suggested_active');
            $('#follow_search').hide('blind', {}, 'fast', function() {
                $('#follow_search').html('');
            });
            
            $('#friend_search').focus();
        } else {
            // Clear the search box
            $('#friend_search').val('');
            $('#friend_search').blur();
            
            // Select this.
            $(this).addClass('suggested_active');
            
            // Get the suggested friends
            $.get('/dashboard/get_suggested_friends', function (data) {
                $('#follow_search').html(data);
                    
                // Hide the profile body if necessary and show the result list
                if ($('.following_profile_body:visible').length > 0) {
                    $('.following_profile_body').hide('fast', function() {
                        $('#follow_search').show('fast');
                    });
                } else {
                    $('#follow_search').show('fast');
                }
                    
                // Add following click handler
                $('.add_following').confirmDiv(function (clicked_elem) {
                    $.get('/dashboard/add_user_following', {
                        following_id: clicked_elem.parent().attr('user_id')
                    }, function (data) {
                        populate_following_list();
                        get_suggested_friends();
                    });
                });
            
                // click handler for getting the profile
                $('.user_entry').click(function(){
                    $.get('/dashboard/get_profile', {
                        user_id: $(this).attr('user_id')
                    }, function (data) {
                        $('#follow_search').hide();
                        $('.following_profile_body').html(data);
                        $('.following_profile_body').show("slow");
                    });
                });
            });
        } 
    });
    
    // Refer to the definition in dashboard_view.
    // Essentially selects the suggested button if necessary at load
    show_suggested_init('#following_content', '.suggested_friends');    
}

// Populates the following list and assigns the click events.
function populate_following_list() {
    
    $.get('/dashboard/get_following', function (data) {
        $('#following_list').html(data);
       
        // Click handler.
        $('#following_list .remove_following').confirmDiv(function (clicked_elem) {
            $.get('/dashboard/remove_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function (data) {
                populate_following_list();
                populate_followers_list();           
            });
        });
        
        $('.user_entry').click(function(){
            $('.suggested_active').removeClass('suggested_active');
            $('#follow_search').hide();
            if(!$(this).hasClass('selected_follower'))
            {
                $('.user_entry.selected_follower').removeClass('selected_follower');
                $(this).addClass('selected_follower');
                
                $.get('/dashboard/get_profile', {
                    user_id: $(this).attr('user_id')
                }, function (data) {
                    $('.following_profile_body').html(data);
                    $('.following_profile_body').show("slow");
                });
            }
        });

    });
}

function initialize_follow_search() {
    // In-field labels;
    $('.in-field_block label').inFieldLabels();
    
    $('#friend_search').keyup(function () {
        $('.following_profile_body').hide('fast');
        $('.suggested_active').removeClass('suggested_active'); // this unselects the "suggested friends" tab first
        $.get('/dashboard/follow_search', {
            needle: $(this).val()
        }, function (data) {
            $('#follow_search').html(data);
            $('#follow_search').show("slow");
            
            
            $('.user_entry').click(function(){ // click handler for getting the profile
                $.get('/dashboard/get_profile', {
                    user_id: $(this).attr('user_id')
                }, function (data) {
                    $('#follow_search').hide();
                    $('.following_profile_body').html(data);
                    $('.following_profile_body').show("slow");
                });
            });
            
            // Click handler.
            $('#follow_search .add_following').confirmDiv(function (clicked_elem) {
                $.get('/dashboard/add_user_following', {
                    following_id: clicked_elem.parent().attr('user_id')
                }, function () {
                    $('#follow_search').hide();
                    $('#follow_search').html('');
                    $('#friend_search').val('');
                    populate_following_list();
                    $('#friend_search').blur();
                });
            });
        });
    });
}