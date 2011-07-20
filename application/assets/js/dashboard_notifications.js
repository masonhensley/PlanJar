function notifications_setup() {
    get_notifications();
}

function get_notifications() {
    $.get('/dashboard/get_notifications', function (data) {
        $('#notifications_list').html(data);
        
        // Read/unread toggle
        $('.notification_entry.unviewed .mark_read').click(function () {
            $(this).html('Mark as unread')
            $(this).unbind('click');
            $(this).click(function () {
                $(this).removeClass('unviewed')
            });
        });
        
        $('.notification_entry .mark_read').not('.notification_entry.unviewed .mark_read').click(function () {
            $(this).html('Mark as read')
            $(this).unbind('click');
            $(this).click(function () {
                $(this).addClass('unviewed')
            });
        });
    });
}