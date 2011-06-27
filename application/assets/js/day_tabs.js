$(function() {
    setup_day_tabs();
});

// Initializes the day of the week tabs.
function setup_day_tabs() {
    // Set up the day of the week tabs.
    $("#day_tabs ul.tabs li:first").addClass("day_selected").show(); //Activate first tab

    //On Click Event
    $("#day_tabs ul.tabs li").click(function() {

        $("#day_tabs ul.tabs li.day_selected").removeClass("day_selected"); //Remove any "day_selected" class
        $(this).addClass("day_selected"); //Add "day_selected" class to selected tab
        
        // Call the callback function.
        on_day_change();
        // update the visible plans for the selected day
        load_visible_plans();
        return false;
    });
}

// Callback function
function on_day_change(day_index) {
    // Switch to the data tab if it isn't active and update the data.
    if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
        $("#map_data_tabs").tabs('select', '#data_tab');
    }
    //get_group_day_data();
    load_visible_plans()
}


// I moved this to home_functions.js because it is needed earlier

// returns the selected day number (relative to current date)

function get_selected_day() {
    return $('#day_tabs ul.tabs li.day_selected a').attr('href')
}

