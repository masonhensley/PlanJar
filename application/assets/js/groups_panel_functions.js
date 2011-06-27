$(function() {
    // Make the groups selectable.
    make_groups_selectable();
});

function make_groups_selectable() {
    $('div.group_selectable_wrapper li').click(function() {
        // unselect plan on right panel
        $('.plan_content').removeClass('selected_plan');
        
        // Make the list tiems togglable.
        if ($(this).hasClass('group_selected')) {
            $(this).removeClass('group_selected');
        } else {
            $(this).addClass('group_selected');
        }
        
        // Call the callback function.
        on_groups_change();
        // Update the visible plans for the selected groups
        load_visible_plans();
    });
    
    // Initialize the clear all and select all button actions.
    $('#clear_all_groups').click(function() {
        $('div.group_selectable_wrapper li.group_selected').removeClass('group_selected');
        on_groups_change();
        // update visible plans
        load_visible_plans();
    });
    $('#select_all_groups').click(function() {
        $('div.group_selectable_wrapper li').addClass('group_selected');
        on_groups_change();
        // update visible plans
        load_visible_plans();
    });
}

// Callback function
function on_groups_change() {
    // Switch to the data tab if it isn't active and update the data.
    if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
        $("#map_data_tabs").tabs('select', '#data_tab');
    }
    get_group_day_data();
    load_visible_plans()
}

// I moved this to home_functions because it is needed earlier


// Returns a list of selected groups.

function get_selected_groups() {
    var return_list = ([]);
    $('div.group_selectable_wrapper li.group_selected').each(function (index, element) {
        return_list.push($(element).attr('group_id'));
    });
    return return_list;
}

