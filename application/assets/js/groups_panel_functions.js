$(function() {
    // Set up the Selectable instance with default options.
    create_selectables();
});

function destroy_selectables() {
    $('#friends_group').selectable('destroy');
    $('#joined_groups').selectable('destroy');
    $('#followed_groups').selectable('destroy');
}

function create_selectable(ul_element) {
    // Make the list tiems selectable.
    $('group_selectable_wrapper li').click(function() {
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

function create_selectables(mode) {
    create_selectable('#friends_group');
    create_selectable('#joined_groups');
    create_selectable('#followed_groups');
}

function on_groups_change(selected_groups) {
    
}