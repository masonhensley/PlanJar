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
        var selected_groups = new JSONObject();
        
        $('div.group_selectable_wrapper li').each(function (index, element) {
           alert('here'); 
        });
        
        on_groups_change(selected_groups);
    });
}

function on_groups_change(selected_groups) {
    
}