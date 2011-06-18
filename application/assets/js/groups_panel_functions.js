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
        
        // Call the change function.
        var selected_groups = ([]);
        
        $('div.group_selectable_wrapper li.group_selected').each(function (index, element) {
           selected_groups.push($(element).attr('group_id'));
        });
        
        console.log(selected_groups);
        
        on_groups_change(selected_groups);
    });
}

function on_groups_change(selected_groups) {
    
}