function notifications_setup() {
    setup_notifications();
    
}

function setup_notifications(){
    // select the unread messages tab
    $('.notifications_tab').removeClass('notifications_tab_selected');
    $('#unread_notifications_tab').addClass('notifications_tab_selected');
    
    // click handler for notification unread and all tabs, callback function populates notifications
    $('.notifications_tab').click(function(){
        if(!$(this).hasClass('notifications_tab_selected'))
        {
            $('.notifications_tab_selected').removeClass('notifications_tab_selected');
            $(this).addClass('notifications_tab_selected');
            get_notifications();
        }
    });
    
    get_notifications(); // this is called to initially populate the list
}

function get_notifications() {
    
    if($('#unread_notifications_tab').hasClass('notifications_tab_selected'))
    {
        $.get('/dashboard/get_unread_notifications', function (data) {
            $('#notifications_list').html(data);
            setup_notification_tabs(data);
        });    
    }else{
        $.get('/dashboard/get_all_notifications', function (data) {
            $('#notifications_list').html(data);
            setup_notification_tabs(data);
        });
    }
    
}

function setup_notification_tabs(data){
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

function update_notification_viewed(id, value) {
    $.get('/dashboard/update_notification_viewed', {
        notif_id: id,
        value: value
    }, function () {
        get_notifications();
    });
}