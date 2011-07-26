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
        
        // User profile link click handler
        $('.user_notif_link').click(function () {
            // Get the user profile and show it
            $.get('/dashboard/get_profile', {
                user_id: $(this).attr('user_id')
            }, function (data) {
                $('#notifications_content .right').html(data);
                $('#notifications_content .right').show("slow");
            });
            
            // Disable the link functionality
            return false;
        });
        
        // Accept handler
        $('.notification_entry .accept').click(function() {
            $.get('/dashboard/accept_notification', {
                notif_id: $(this).parent().attr('notif_id'),
                event_id: $(this).parent().attr('event_id')
            }, function (data) {
                get_notifications();
            });
        });
    });
}

function update_notification_viewed(id, value) {
    $.get('/dashboard/update_notification_viewed', {
        notif_id: id,
        value: value
    });
}