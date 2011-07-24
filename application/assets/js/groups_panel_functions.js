$(function() {
    // Make the groups selectable.
    initialize_selectable_groups();
});

function initialize_selectable_groups() {
    
    //default tab used is the city_tab
    set_city_tab();
    
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
    
    update_groups_and_locations();
}

// Callback function
function on_groups_change() {
    show_data_container('#group_data');
    $('.city_active').removeClass('city_active'); // unselect the city tab
    update_groups_and_locations();// this should update the graphs so they match what is selected
}

// this is the "use current location" tab. clicking it de-selects all other group tabs and uses the current location
function set_city_tab()
{
    $('.city_tab').click(function(){
        if($(this).hasClass('city_active'))
        {
            $(this).removeClass('city_active');
        }else{
            $('.selected_group').removeClass('selected_group');
            $('.city_active').removeClass('city_active');
            $(this).addClass('city_active');
            
            // Change to select one group
            $('#select_one_group').click();
        }
        update_groups_and_locations();
    });
}

function initialize_one_group_select() {
    $('.groups_wrapper .selectable_group').unbind('click');
    $('.groups_wrapper .selectable_group.selected_group').removeClass('selected_group');
    $('.groups_wrapper .selectable_group').click(function() {
        $('.groups_wrapper .selectable_group.selected_group').not(this).removeClass('selected_group');
        if ($(this).hasClass('selected_group')) {
            $(this).removeClass('selected_group');
        } else {
            $(this).addClass('selected_group');
        }
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
        on_groups_change();
    });
}