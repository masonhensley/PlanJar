$(function () {
    populate_edit_groups_list();
});

function populate_edit_groups_list() {
    $.get('/dashboard/get_following_groups', function (data) {
        $('#edit_groups_list').html(data);
        
        // Make groups selectable
        $('#edit_groups_list .group_entry').click(function() {
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
        group_click_handler('.remove_joined', 'remove_group_following');
    });
}

function group_click_handler(button_class, dashboard_function) {
    $('#edit_groups_list ' + button_class).click(function () {
        $(this).text('- You sure?');
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