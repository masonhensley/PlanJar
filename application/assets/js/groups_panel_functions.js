$(function() {
    // Set up the Selectable instance with default options.
    create_selectables();
});

function create_selectables() {
    // Make the list tiems selectable.
    $('div.group_selectable_wrapper li').click(function() {
        // Toggle the selection.
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
        on_groups_change();
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

function on_groups_change() {
    // Initialize the group list.
    var selected_groups = ([]);
    $('div.group_selectable_wrapper li.group_selected').each(function (index, element) {
        selected_groups.push($(element).attr('group_id'));
    });
    
    console.log(selected_groups);
}