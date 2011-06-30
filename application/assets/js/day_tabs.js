$(function() {
    setup_day_tabs();
});

// Initializes the day of the week tabs.
function setup_day_tabs() {
    // Set up the day of the week tabs.
    $("div.days_panel .day:first").addClass("day_selected").select(); //Activate first tab

    //On Click Event
    $("div.days_panel .day").click(function() {

        $('.selected_plan').removeClass('selected_plan'); // remove selected plan on right panel

        $("div.days_panel .day_selected").removeClass("day_selected"); //Remove any "day_selected" class
        $(this).addClass("day_selected"); //Add "day_selected" class to selected tab
        
        // Call the callback function.
        on_day_change();
        
        // update the visible plans for the selected day
        load_visible_plans();
        
        return false;
    });
}

// Callback function
function on_day_change() {
    show_data_container('#group_data');
    get_group_day_data();
    load_visible_plans()
}

