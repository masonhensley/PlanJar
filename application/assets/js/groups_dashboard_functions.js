$(function () {
    populate_edit_groups_list();
});
    
function populate_edit_groups_list() {
    $.get('/dashboard/get_following_groups', function (data) {
        $('#edit_groups_list').html(data);
    });
}