function groups_setup() {
    populate_edit_groups_list();
    initialize_group_search();
}

function initialize_group_search() {
    // click handler for suggest groups
    $('.suggest_groups').click(function(){
        if($(this).hasClass('suggest_groups_active'))
        {
            $(this).removeClass('suggest_groups_active');
            initialize_group_search();
        }else{
           
            $(this).addClass('suggest_groups_active');
            $.get('/dashboard/suggest_groups',{}, function(data){
                $('#find_groups_list').html(data);
                 alert('success');
            })
        }
    });
    
    // Search for groups on keyup
    $('#group_search').keyup(function () {
        $.get('/dashboard/group_search', {
            needle: $(this).val()
        }, function (data) {
            $('#find_groups_list').html(data);
            
            // Click handler.
            $('#find_groups_list .add_following').click(function () {
                $(this).text('You sure?');
                $(this).unbind('click');
                $(this).click(function () {
                    $.get('/dashboard/add_group_following', {
                        group_id: $(this).parent().attr('group_id')
                    }, function () {
                        $('#find_groups_list').html('');
                        $('#group_search').val('');
                        populate_edit_groups_list();
                        $('#group_search').blur();
                    });
                });
            });

            // Make groups selectable
            $('#find_groups_list .group_entry').click(function() {
                // Unselect other groups
                $('#find_groups_list .group_entry.selected_group').removeClass('selected_group');
                                
                $('.group_entry.selected_group').removeClass('selected_group');
                $(this).addClass('selected_group');
                $.get('/dashboard/get_group_details', {
                    group_id: $(this).attr('group_id')
                }, function (data) {
                    $('#groups_content .middle').html(data);
                });
            });
        });
    });
}

function populate_edit_groups_list() {
    $.get('/dashboard/get_following_groups', function (data) {
        $('#edit_groups_list').html(data);
        
        // Make groups selectable
        $('#edit_groups_list .group_entry').click(function() {
            // Unselect other groups
            $('#find_groups_list .group_entry.selected_group').removeClass('selected_group');
                                
            $('.group_entry.selected_group').removeClass('selected_group');
            $(this).addClass('selected_group');
            $.get('/dashboard/get_group_details', {
                group_id: $(this).attr('group_id')
            }, function (data) {
                $('#groups_content .middle').html(data);
            });
        });
        
        // Click handlers
        group_click_handler('.add_following', 'add_group_following');
        group_click_handler('.remove_following', 'remove_group_following');
        group_click_handler('.remove_joined', 'remove_group_joined');
        group_click_handler('.add_joined', 'add_group_joined');
    });
}

function group_click_handler(button_class, dashboard_function) {
    $('#edit_groups_list ' + button_class).click(function () {
        $(this).text('You sure?');
        $(this).unbind('click');
        $(this).click(function () {
            $.get('/dashboard/' + dashboard_function, {
                group_id: $(this).parent().attr('group_id')
            }, function (data) {
                populate_edit_groups_list();
            });
        });
    });
}