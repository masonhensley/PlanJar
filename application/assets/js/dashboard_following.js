$(function () {
    initialize_suggested_friends();
});

// Run when the tab is selected
function following_setup() {
    populate_following_list();
}

// This sets up the suggested friends list
function initialize_suggested_friends()
{
    // In-field label
    $('#following_content .in-field_block label').inFieldLabels();
    
    // Search for friends
    $('#friend_search').keyup(function () {
        
        $('.suggested_active').removeClass('suggested_active'); // this unselects the "suggested friends" tab
        
        $.get('/dashboard/follow_search', {
            needle: $(this).val()
        }, function (data) {
            $('#follow_search').html(data);
            
            // Hide the profile body if necessary and show the search results
            if ($('.following_profile_body').is(':visible')) {
                $('.following_profile_body').hide('fast', function() {
                    $('#follow_search').show('blind', {}, 'fast');
                });
            } else {
                $('#follow_search').show('blind', {}, 'fast');
            }
            
            // Friend search user click handler
            $('#follow_search .user_entry').click(suggested_search_click);
            
            // Follow click handler
            $('#follow_search .add_following').confirmDiv(function (clicked_elem) {
                var following_id = clicked_elem.parent().attr('user_id');
                
                $.get('/dashboard/add_user_following', {
                    'following_id': following_id
                }, function () {
                    populate_following_list(function() {
                        // Click the resulting entry on the left
                        $('#following_list .user_entry[user_id="' + following_id + '"]').click();
                    });
                });
            });
        });
    });
    
    // Toggle suggested friends
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
            // start spinner
            var target = document.getElementById('follow_search');
            var opts = spinner_options();
            
            var spinner = new Spinner(opts).spin(target);
            
            // Clear the search box
            $('#friend_search').val('');
            $('#friend_search').blur();
            
            // Select this.
            $(this).addClass('suggested_active');
            
            // Get the suggested friends
            populate_suggested_friends(function(){
                spinner.stop();
            });
            
        } 
    });
    
    // Refer to the definition in dashboard_view.
    // Essentially selects the suggested button if necessary at load
    show_suggested_init('#following_content', '.suggested_friends');    
}

// Populates the suggested friends and assigns the click handlers
function populate_suggested_friends() {
    $.get('/dashboard/get_suggested_friends', function (data) {
        $('#follow_search').html(data);
                    
        // Hide the profile body if necessary and show the result list
        if ($('.following_profile_body').is(':visible')) {
            $('.following_profile_body').hide('fast', function() {
                $('#follow_search').show('blind', {}, 'fast');
            });
        } else {
            $('#follow_search').show('blind', {}, 'fast');
        }
                    
        // Add following click handler
        $('.add_following').confirmDiv(function (clicked_elem) {
            $.get('/dashboard/add_user_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function (data) {
                populate_suggested_friends();
                populate_following_list();
            });
        });
            
        // click handler for getting the profile
        $('#follow_search .user_entry').click(suggested_search_click);
    });
}

// Modularized click handler for suggested/searched friends
function suggested_search_click() {
    // Capture the user id
    var user_id = $(this).attr('user_id');

    // Deselect any of the selected user's followers
    $('#following_list .selected_follower').removeClass('selected_follower');
                    
    // Show the profile
    $.get('/dashboard/get_profile', {
        'user_id': user_id,
        'force_accept_button': true
    }, function (data) {
        $('.following_profile_body').html(data);
        $('.suggested_friends').removeClass('suggested_active');
                        
        $('#follow_search').hide('blind', {}, 'fast', function() {
            $('.following_profile_body').show("fast");
        });
        
        // Add following click handler
        $('.following_profile_body .add_following').confirmDiv(function (clicked_elem) {
            $.get('/dashboard/add_user_following', {
                following_id: user_id
            }, function (data) {
                populate_following_list(function() {
                    // Click on the newly added following entry
                    console.log($('#following_list .user_entry[user_id="' + user_id + '"]'));
                    console.log('#following_list .user_entry[user_id="' + user_id + '"]');
                    $('#following_list .user_entry[user_id="' + user_id + '"]').click();
                });
            });
        });       
    });
}

// Populates the following list and assigns the click events.
function populate_following_list(callback) {
    $.get('/dashboard/get_following', function (data) {
        $('#following_list').html(data);
       
        // Unfollow click handler
        $('#following_list .remove_following').confirmDiv(function (clicked_elem) {
            $.get('/dashboard/remove_following', {
                following_id: clicked_elem.parent().attr('user_id')
            }, function (data) {
                // Hide the profile body
                $('.following_profile_body').hide();
                
                populate_following_list();
            });
        });
        
        // User entry click handler
        $('#following_list .user_entry').click(function(){
            $('.suggested_active').removeClass('suggested_active');
            $('#follow_search').hide();
            if(!$(this).hasClass('selected_follower'))
            {
                $('.selected_follower').removeClass('selected_follower');
                $(this).addClass('selected_follower');
            }
                
            // Not in if statement to allow re-clicking
            $.get('/dashboard/get_profile', {
                user_id: $(this).attr('user_id')
            }, function (data) {
                // Hide and reload the data
                $('.following_profile_body').hide();
                $('.following_profile_body').html(data);
                
                // Hide if necessary and show
                if ($('#follow_search').is(':visible')) {
                    $('#follow_search').hide('blind', {}, 'fast', function() {
                        $('.following_profile_body').show("fast");
                    });
                } else {
                    $('.following_profile_body').show("fast");
                }
            });
        });
        
        if (callback != undefined) {
            callback();
        }
    });
}