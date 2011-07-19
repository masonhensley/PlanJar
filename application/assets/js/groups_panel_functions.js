$(function() {
    // Make the groups selectable.
    initialize_selectable_groups();
});

function initialize_selectable_groups() {
    // Divset
    $('#group_select_type').divSet();
    $('#select_one_group').click();
    
    initialize_one_group_select();
    
    $('#select_one_group').click(function () {
        initialize_one_group_select();
    });
    $('#select_mult_groups').click(function () {
        initialize_mult_groups_select();
    });
    
    // Initialize the clear all and select all button actions.
    $('#clear_all_groups').click(function() {
        $('.groups_wrapper .selected_group').removeClass('selected_group');
        on_groups_change();
        // update visible plans
        load_visible_plans();
    });
    $('#select_all_groups').click(function() {
        $('.groups_wrapper .selectable_group').addClass('selected_group');
        $('#select_mult_groups').click();
        on_groups_change();
        // update visible plans
        load_visible_plans();
    });
}

// Callback function
function on_groups_change() {
    show_data_container('#group_data');
    get_group_day_data();
    load_visible_plans()
}

function initialize_one_group_select() {
    $('.groups_wrapper .selectable_group').unbind('click');
    $('.groups_wrapper .selectable_group.selected_group').removeClass('selected_group');
    $('.groups_wrapper .selectable_group').click(function() {
        $('.groups_wrapper .selectable_group.selected_group').removeClass('selected_group');
        $(this).addClass('selected_group');
        on_groups_change();
    });
}

function initialize_mult_groups_select() {
    $('.groups_wrapper .selectable_group').unbind('click');
    $('.groups_wrapper .selectable_group').click(function() {
        // unselect plan on right panel
        //$('.plan_content').removeClass('selected_plan');
        // clear the plan data
        show_empty_plan_data();
        
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
}