function notifications_setup() {
    get_notifications();
}

function get_notifications() {
    $.get('/dashboard/get_notifications', function (data) {
        $('#notifications_list').html(data);
        
        // Read/unread toggle
        $('.notification_entry .mark_read').click(function () {
            if ($(this).parent().hasClass('unviewed')) {
                $(this).parent().removeClass('unviewed');
                $(this).html('Mark as unread');
                update_notification_viewed($(this).parent().attr('notif_id'), 1);
            } else {
                $(this).parent().addClass('unviewed');
                $(this).html('Mark as read');
                update_notification_viewed($(this).parent().attr('notif_id'), 0);
            }
        });
    });
}

function update_notification_viewed(id, value) {
    $.get('/dashboard/update_notification_viewed', {
        notif_id: id,
        value: value
    });
}