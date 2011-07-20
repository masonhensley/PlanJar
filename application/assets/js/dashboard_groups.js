$(function() {
    initialize_group_search();
});

function groups_setup() {
    populate_edit_groups_list();
}

function initialize_group_search() {
    // In-field labels
    $('#groups_content .right_header .in-field_block label').inFieldLabels();
    
    // click handler for suggest groups
    $('.suggest_groups').click(function(){
        if($(this).hasClass('suggest_groups_active'))
        {
            $('.suggest_groups_active').removeClass('suggest_groups_active');
            $('#find_groups_list').html('');
        }else{
            $('.suggest_groups').addClass('suggest_groups_active');
            $.get('/dashboard/suggest_groups', function(data){
                $('#find_groups_list').html(data);
                group_select_click_handler();
            });
        }
    });
    show_suggested_init('#groups_content', '.suggest_groups');
    
    // Search for groups on keyup
    $('#group_search').keyup(function () {
        $('.suggest_groups_active').removeClass('suggest_groups_active');
        $.get('/dashboard/group_search', {
            needle: $(this).val()
        }, function (data) {
            $('#find_groups_list').html(data);
            
            group_select_click_handler();
        });
    });
}

function group_select_click_handler()
{
    $('#find_groups_list .group_entry').click(function() {
        // Unselect other groups
        //$('#find_groups_list .group_entry.selected_group').removeClass('selected_group'); 
        //$('.group_entry.selected_group').removeClass('selected_group');
        
        if(!$(this).hasClass('selected_group'))
        {
            $('.selected_group').removeClass('selected_group'); 
            $(this).addClass('selected_group');
            $.get('/dashboard/get_group_details', {
                group_id: $(this).attr('group_id')
            }, function (data) {
                $('#groups_content .middle').html(data);
            });
        }
    });
    
    // Click handler.
    $('#find_groups_list .add_following').click(function () {
        $(this).text('You sure?');
        $(this).unbind('click');
        $(this).click(function () {
            $.get('/dashboard/add_group_following', {
                //group_id: $(this).parent().attr('group_id')
                group_id: $('.selected_group').attr('group_id')
            }, function () {
                $('#find_groups_list').html('');
                $('#group_search').val('');
                populate_edit_groups_list();
                $('#group_search').blur();
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
                
                // Join handler (special case)
                $('#groups_content .middle .add_joined').click(function () {
                    $(this).text('You sure?');
                    $(this).unbind('click');
                    $(this).click(function () {
                        $.get('/dashboard/add_group_joined', {
                            group_id: $('#edit_groups_list .selected_group').attr('group_id')
                        }, function (data) {
                            $('#groups_content .middle').html('');
                            populate_edit_groups_list();
                        });
                    });
                });
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
                group_id: $(this).parent().parent().attr('group_id')
            }, function (data) {
                populate_edit_groups_list();
            });
        });
    });
}