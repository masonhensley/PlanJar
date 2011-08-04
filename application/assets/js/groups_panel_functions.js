$(function() {
    // Make the groups selectable.
    initialize_selectable_groups();
});

// Initializes the groups/networks panel
function initialize_selectable_groups() {
    // Network tab click handler
    $('.network_tab').click(function(){
        // Make the tabs selectable
        if($(this).hasClass('network_active'))
        {
            // Deselect the tab
            $(this).removeClass('network_active');
            
            $('#info_content').html('<img src="/application/assets/images/center_display.png">');
        } else {
            // Deselect all controlls
            deselect_all_controlls();
            
            // Select this network
            $(this).addClass('network_active');
            
            // Change to select one group
            $('#select_one_group').click();
            
            update_groups_and_locations();
        }
    });
    
    // Divset
    $('#group_select_type').divSet();
    
    // One/multiple group select click handlers
    $('#select_one_group').click(function () {
        initialize_one_group_select(true);
    });
    $('#select_mult_groups').click(function () {
        initialize_mult_groups_select();
    });
}

// Callback function
function on_groups_change() {
    update_groups_and_locations(); // this should update the graphs so they match what is selected
}

// Initialize the groups such that up to one is selectable at a time
function initialize_one_group_select(initial_update) {
    $('.groups_wrapper .selectable_group').unbind('click');
    $('.groups_wrapper .selectable_group.selected_group').removeClass('selected_group');
    
    if (initial_update == undefined) {
        on_groups_change();
    }
    
    $('.groups_wrapper .selectable_group').click(function() {
        $('.network_active').removeClass('network_active'); // unselect the city tab
     
        // Deselect all other selected groups
        $('.groups_wrapper .selected_group').not(this).removeClass('selected_group');
     
        // Toggle this group
        if ($(this).hasClass('selected_group')) {
            $(this).removeClass('selected_group');
        } else {
            $(this).addClass('selected_group');
            on_groups_change();
        }
        
    });
}

// Initializes the groups such that any number can be selected at a time
function initialize_mult_groups_select() {
    $('.groups_wrapper .selectable_group').unbind('click');
    $('.groups_wrapper .selectable_group').click(function() {
        $('.network_active').removeClass('network_active'); // unselect the city tab
        
        // unselect plan on right panel
        $('.plan_content').removeClass('selected_plan');
        
        // Make the list items togglable.
        if ($(this).hasClass('selected_group')) {
            $(this).removeClass('selected_group');
        } else {
            $(this).addClass('selected_group');
        }
        on_groups_change();
    });
}