$(function() {
    // click handler for notification unread and all tabs, callback function populates notifications
    $('.notifications_tab').click(function(){
        $('.notifications_tab_selected').removeClass('notifications_tab_selected');
        $(this).addClass('notifications_tab_selected');
        get_notifications();
    });
});

// Called when the notifications tab is clicked
function notifications_setup() {
    // Click the unread tab when the tab is loaded
    $('#unread_notifications_tab').click();
}

// Populates the notifications
function get_notifications() {
    if($('#unread_notifications_tab').hasClass('notifications_tab_selected'))
    {
        // Unread
        $.get('/dashboard/get_unread_notifications', function (data) {
            $('#notifications_list').html(data);
            notification_click_handlers();
        });    
    } else {
        // All
        $.get('/dashboard/get_all_notifications', function (data) {
            $('#notifications_list').html(data);
            notification_click_handlers();
        });
    }
}

// Click handlers for notifications (view profile link, mark unread, etc.
function notification_click_handlers(){
    // Read/unread toggle
    $('.notification_entry .mark_read').click(function () {
        var bool = $(this).parent().hasClass('unviewed') ? 1 : 0;
        update_notification_viewed($(this).parent().attr('notif_id'), bool);
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
            data = $.parseJSON(data);
                
            if (data.status == 'success') {
                // Success. Repopulate notifications
                get_notifications();
            } else {
                // Plan conflict
                open_conflict_modal(data, function() {
                    get_notifications();
                });
            }
        });
    });
}

// Does what it says
function update_notification_viewed(id, value) {
    $.get('/dashboard/update_notification_viewed', {
        notif_id: id,
        value: value
    }, function () {
        get_notifications();
    });
}