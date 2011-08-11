$(function() {
    initialize_group_search();
});

function groups_setup() {
    populate_edit_groups_list();
    
    // show the + Create Group button
    $('#create_group').show("fast");
}

function initialize_group_search() {
    // In-field labels
    $('#groups_content .right_header .in-field_block label').inFieldLabels();
    
    // click handler for suggest groups
    $('.suggest_groups').click(function(){
        if($(this).hasClass('suggest_groups_active'))
        {
            // Deactivate the suggest button and hide the suggested list
            $('.suggest_groups').removeClass('suggest_groups_active');
            $('#find_groups_list').hide("fast", function() {
                $('#find_groups_list').html(''); 
            });
        } else {
            // Suggest groups
            $('.suggest_groups').addClass('suggest_groups_active');
            $.get('/dashboard/suggest_groups', function(data){
                $('#find_groups_list').html(data);
                $('#find_groups_list').show("fast");
                group_select_click_handler();
            });
        }
    });
    
    // Refer to the definition in dashboard_view.
    // Essentially selects the suggested button if necessary at load
    show_suggested_init('#groups_content', '.suggest_groups');
    
    // Search for groups on keyup
    $('#group_search').keyup(function () {
        // Deactivate the suggest button and hide the suggested list
        $('suggest_groups ').removeClass('suggest_groups_active');
        $('#find_groups_list').hide("fast", function() {
            $('#find_groups_list').html(''); 
        });
        
        $.get('/dashboard/group_search', {
            needle: $(this).val()
        }, function (data) {
            $('#find_groups_list').html(data);
            $('#find_groups_list').show('fast');
            
            group_select_click_handler();
        });
    });
}

// Handles clicking on your groups
function group_select_click_handler()
{
    $('#find_groups_list .group_entry').click(function() {
        // Unselect other groups and select selected (if it isn't already)
        if(!$(this).hasClass('selected_group'))
        {
            $('.selected_group').removeClass('selected_group'); 
            $(this).addClass('selected_group');
            
            $.get('/dashboard/get_group_details', {
                group_id: $(this).attr('group_id')
            }, function (data) {
                // Hide visible middle panel (if applicable) and show the new middle panel
                if ($('#groups_content .middle:visible').length > 0) {
                    $('#groups_content .middle').hide('blind', {
                        direction: 'up'
                    }, 'fast', function() {
                        $('#groups_content .middle').html(data);
                        $('#groups_content .middle').show('blind', {
                            direction: 'down'
                        });
                    });
                } else {
                    $('#groups_content .middle').html(data);
                    $('#groups_content .middle').show('blind', {
                        direction: 'down'
                    });
                }
            });
        }
    });
    
    // Add following click handler.
    $('#find_groups_list .add_following').confirmDiv(function(clicked_elem) {
        $.get('/dashboard/add_group_following', {
            group_id: clicked_elem.parent().attr('group_id')
        }, function (data) {
            populate_edit_groups_list();
                            
            // Blur out the suggested groups (not always necessary, but easy)
            $('#find_groups_list').html('');
            $('#group_search').val('');
            $('#group_search').blur();
        });
    });
}

function populate_edit_groups_list() {
    $.get('/dashboard/get_following_groups', function (data) {
        $('#edit_groups_list').html(data);
        
        // Make groups selectable
        $('#edit_groups_list .group_entry').click(function() {
            // Unselect other groups
            $('.middle').hide();
            if(!$(this).hasClass('selected_group'))
            {
                $('.selected_group').removeClass('selected_group'); 
                $(this).addClass('selected_group');
                $.get('/dashboard/get_group_details', {
                    group_id: $(this).attr('group_id')
                }, function (data) {
                    $('#groups_content .middle').html(data);
                    $('.middle').show("fast");
                    
                    // Button click handlers
                    $('#groups_content .remove_following').confirmDiv(function() {
                        $.get('/dashboard/remove_group_following', {
                            group_id: $('.group_profile_header').attr('group_id')
                        }, function (data) {
                            populate_edit_groups_list();
                            $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                            
                            // Blur out the suggested groups (not always necessary, but easy)
                            $('#find_groups_list').html('');
                            $('#group_search').val('');
                            $('#group_search').blur();
                        });
                    });
                    
                    $('#groups_content .remove_joined').confirmDiv(function() {
                        $.get('/dashboard/remove_group_joined', {
                            group_id: $('.group_profile_header').attr('group_id')
                        }, function (data) {
                            populate_edit_groups_list();
                            $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                            
                            // Blur out the suggested groups (not always necessary, but easy)
                            $('#find_groups_list').html('');
                            $('#group_search').val('');
                            $('#group_search').blur();
                        });
                    });
                    
                    $('#groups_content .add_joined').confirmDiv(function() {
                        $.get('/dashboard/add_group_joined', {
                            group_id: $('.group_profile_header').attr('group_id')
                        }, function (data) {
                            populate_edit_groups_list();
                            $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
                            
                            // Blur out the suggested groups (not always necessary, but easy)
                            $('#find_groups_list').html('');
                            $('#group_search').val('');
                            $('#group_search').blur();
                        });
                    });
                    
                    // Invite people
                    $('#groups_content .middle .invite_people').click(function() {
                        open_invite_modal('group', $('.group_profile_header').attr('group_id'), $('.group_profile_header').attr('priv_type'));
                    })
                });
            }
        });
    });
}

function group_click_handler(button_class, dashboard_function) {
    $(button_class).confirmDiv(function () {
        $.get('/dashboard/' + dashboard_function, {
            group_id: $('.group_profile_header').attr('group_id')
        }, function (data) {
            populate_edit_groups_list();
            if(dashboard_function == 'remove_group_following')
            {
                $('.middle').html("<div style=\"text-align:center; color:gray; position:relative; top:3px;\"> Select a group on the left or right to see its profile </div>");
            }
            // Blur out the suggested groups (not always necessary, but easy)
            $('#find_groups_list').html('');
            $('#group_search').val('');
            $('#group_search').blur();
        });
    });
}