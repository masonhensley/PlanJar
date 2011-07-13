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
        $('#edit_groups_list .add_following').click(function () {
            if ($(this).text() == '+ Follow') {
                $(this).text('+ You sure?');
            } else {
                $.get('/dashboard/add_user_following', {
                    following_id: $(this).parent().attr('user_id')
                }, function (data) {
                    populate_followers_list();
                    populate_following_list();
                });
            }
        });
    });
    
}