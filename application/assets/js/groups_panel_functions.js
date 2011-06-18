$(function() {
    // Make the groups selectable.
    make_selectable();
});

function make_selectable() {
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
}