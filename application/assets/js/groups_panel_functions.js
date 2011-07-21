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
}

// Callback function
function on_groups_change() {
    show_data_container('#group_data');
    //get_group_day_data();
    //load_visible_plans();
    update_groups_and_locations();// this should update the graphs so they match what is selected
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
        $('.plan_content').removeClass('selected_plan');
        
        // Make the list items togglable.
        if ($(this).hasClass('selected_group')) {
            $(this).removeClass('selected_group');
        } else {
            $(this).addClass('selected_group');
        }
        update_groups_and_locations();
    });
}