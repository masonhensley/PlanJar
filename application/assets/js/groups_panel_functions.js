$(function() {
    // Make the groups selectable.
    make_groups_selectable();
});

function make_groups_selectable() {
    $('div.group_selectable_wrapper li').click(function() {
        // Make the list tiems togglable.
        if ($(this).hasClass('group_selected')) {
            $(this).removeClass('group_selected');
        } else {
            $(this).addClass('group_selected');
        }
        
        // Initialize the group list.
        var selected_groups = ([]);
        $('div.group_selectable_wrapper li.group_selected').each(function (index, element) {
            selected_groups.push($(element).attr('group_id'));
        });
        
        // Call the callback function.
        on_groups_change(selected_groups);
    });
    
    // Initialize the clear all and select all button actions.
    $('#clear_all_groups').click(function() {
        $('div.group_selectable_wrapper li.group_selected').removeClass('group_selected');
        on_groups_change();
    });
    $('#select_all_groups').click(function() {
        $('div.group_selectable_wrapper li').addClass('group_selected');
        on_groups_change();
    });
}

// Callback function
function on_groups_change(selected_groups) {
    // Get the data based on groups and the day from the server.
    $.get('/home/get_group_day_data', {
        'selected_groups': selected_groups,
        'selected_day': $('#day_tabs .day_selected a').attr('href')
    }, function (data) {
        // Replace the data and show the data tab.
        $('#data_tab').replace(data)
        $("#map_data_tabs").tabs('select', '#data_tab');
    });
}