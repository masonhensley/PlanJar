$(function() {
    initialize_group_search();
});

// Called when the tab is selected
function groups_setup(action_arg) {
    populate_edit_groups_list(function() {
        if (action_arg == 'suggested') {
            $('.suggest_groups').click();
        } else if ($('#edit_groups_list .group_entry[group_id="' + action_arg + '"]').length > 0) {
            // Seek to that group
            $('#edit_groups_list .group_entry[group_id="' + action_arg + '"]').click();
        } else if (action_arg != undefined && action_arg != '') {
            // Unlisted group
            show_group_profile(action_arg);
        }
    });
    
    // show the + Create Group button
    $('#create_group').show("fast");
}

// Sets up the suggest people toggle and search box
function initialize_group_search() {
    // In-field labels
    $('#groups_content .right_header .in-field_block label').inFieldLabels();
    
    // click handler for suggest groups
    $('.suggest_groups').click(function(){
        if($(this).hasClass('suggest_groups_active'))
        {
            // Deactivate the suggest button and hide the suggested list
            $('.suggest_groups').removeClass('suggest_groups_active');
            $('#find_groups_list').hide('blind', {}, 'fast', function() {
                $('#find_groups_list').html(''); 
            });
            $('#group_search').focus();
        } else {
            
            // start spinner
            var group_suggest_opts = spinner_options();
            var group_suggest_target = document.getElementById('suggest_groups_spinner');
            var group_suggest_spinner = new Spinner(group_suggest_opts).spin(group_suggest_target);
            
            // Clear and blur the search box
            $('#group_search').val('');
            $('#group_search').blur();
            
            // Suggest groups
            $('.suggest_groups').addClass('suggest_groups_active');
            $.get('/dashboard/suggest_groups', function(data) {
                $('#find_groups_list').html(data);
                $('#find_groups_list').show('blind', {}, 'fast');
                
                group_select_click_handler();
            }).complete(function(){
                group_suggest_spinner.stop(); // stop spinner
            });
        }
    });
    
    // Search for groups on keyup
    $('#group_search').keyup(function () {
        
        $('#find_groups_list').hide();
        
        // start spinner
        var group_suggest_opts = spinner_options();
        var group_suggest_target = document.getElementById('suggest_groups_spinner');
        var group_suggest_spinner = new Spinner(group_suggest_opts).spin(group_suggest_target);
        
        // Deactivate the suggest button and hide the suggested list
        $('.suggest_groups').removeClass('suggest_groups_active');
        
        $.get('/dashboard/group_search', {
            needle: $(this).val()
        }, function (data) {
            $('#find_groups_list').html(data);
            $('#find_groups_list').show('blind', {}, 'fast');
            
            group_select_click_handler();
        }).complete(function(){
            group_suggest_spinner.stop();
        });
    });
}

// Handles clicking found groups
function group_select_click_handler(group_id)
{
    $('#find_groups_list .group_entry').click(function() {
        // Unselect other groups and select selected (if it isn't already)
        if(!$(this).hasClass('selected_group'))
        {
            $('.selected_group').removeClass('selected_group'); 
            $(this).addClass('selected_group');
            
            show_group_profile($(this).attr('group_id'));
        }
    });
}

// Populates the list of the user's groups and assigns the click events
function populate_edit_groups_list(callback) {
    $.get('/dashboard/get_following_groups', function (data) {
        $('#edit_groups_list').html(data);
        
        // Make groups selectable
        $('#edit_groups_list .group_entry').click(function() {

            // Unselect other groups
            $('#edit_groups_list .middle').hide();
            if(!$(this).hasClass('selected_group'))
            {
                // Select this
                $('.selected_group').removeClass('selected_group'); 
                $(this).addClass('selected_group');
                
                show_group_profile($(this).attr('group_id'));
            }
            
        });
        
        if (callback != undefined) {
            callback();
        }
    });
}

// Shows the group profile in the middle
function show_group_profile(group_id) {
    // start the spinner
    var select_group_opts = spinner_options();
    var select_group_target = document.getElementById('group_middle_spinner');
    var select_group_spinner = new Spinner(select_group_opts).spin(select_group_target);
    
    // Load the view
    $.get('/dashboard/get_group_details', {
        'group_id': group_id
    }, function (data) {
        if (data != '') {
            $('#groups_content .middle').hide();
            $('#groups_content .middle').html(data);
            $('#groups_content .middle').show("fast");
                    
            // Remove following handler
            $('#groups_content .remove_following').confirmDiv(function() {
                $.get('/dashboard/remove_group_following', {
                    group_id: $('.group_profile_header').attr('group_id')
                }, function (data) {
                    populate_edit_groups_list();
                    $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                });
            });
                    
            // Remove joined handler
            $('#groups_content .remove_joined').confirmDiv(function() {
                $.get('/dashboard/remove_group_joined', {
                    group_id: $('.group_profile_header').attr('group_id')
                }, function (data) {
                    populate_edit_groups_list();
                    $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                });
            });
                    
            // Add joined handler
            $('#groups_content .add_joined').confirmDiv(function() {
                $.get('/dashboard/add_group_joined', {
                    group_id: $('.group_profile_header').attr('group_id')
                }, function (data) {
                    populate_edit_groups_list();
                    $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                });
            });
            
            // Request add joined handler
            $('#groups_content .request_to_join').confirmDiv(function() {
                $.get('/dashboard/request_join_group', {
                    group_id: $('.group_profile_header').attr('group_id')
                }, function (data) {
                    populate_edit_groups_list();
                    $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                });
            });
                    
            // Invite people
            $('#groups_content .middle .invite_people').click(function() {
                open_invite_modal('group', $('.group_profile_header').attr('group_id'), $('.group_profile_header').attr('priv_type'));
            })
                    
            // set the view list click handler
            $('#view_group_list').click(function(){
                populate_group_member_panel(group_id);
            });
            
            // Add following click handler.
            $('.group_bottom_text .add_following').confirmDiv(function(clicked_elem) {
                $.get('/dashboard/add_group_following', {
                    'group_id': group_id
                }, function (data) {
                    // Repopulate the groups list and select the recently followed group
                    populate_edit_groups_list(function() {
                        $('#edit_groups_list .group_entry[group_id = "' + group_id + '"]').click();
                    });
            
                    // Clear and hide search boxes
                    $('#group_search').val('');
                    $('#group_search').keyup();
                    $('#group_search').focus();
                });
            });
        }          
    }).complete(function(){
        select_group_spinner.stop(); // stop the spinner
    });
}