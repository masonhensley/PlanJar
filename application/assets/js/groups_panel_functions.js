$(function() {
    // Make the groups selectable.
    initialize_selectable_groups();
});

function initialize_selectable_groups() {
    $('.groups_wrapper .selectable_group').click(function() {
        // unselect plan on right panel
        $('.plan_content').removeClass('selected_plan');
        
        // Make the list items togglable.
        if ($(this).hasClass('selected_group')) {
            $(this).removeClass('selected_group');
        } else {
            $(this).addClass('selected_group');
        }
        
        // Call the callback function.
        on_groups_change();
        // Update the visible plans for the selected groups
        load_visible_plans();
    });
    
    // Initialize the clear all and select all button actions.
    $('#clear_all_groups').click(function() {
        $('.group_wrapper .selected_group').removeClass('selected_group');
        on_groups_change();
        // update visible plans
        load_visible_plans();
    });
    $('#select_all_groups').click(function() {
        $('.group_wrapper .selected_group').addClass('selected_group');
        on_groups_change();
        // update visible plans
        load_visible_plans();
    });
}

// Callback function
function on_groups_change() {
    show_data_container('#group_data');
    $('.selected_plan').removeClass('selected_plan');
    get_group_day_data();
    load_visible_plans()
}

