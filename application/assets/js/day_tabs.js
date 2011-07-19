$(function () {
    initialize_day_tabs();
});

// Initializes the day of the week tabs.
function initialize_day_tabs() {
    // Populate the initial days.
    get_new_days(0);
}

function initialize_day_tab_rules() {
    // Set up the day of the week tabs.
    $("div.days_panel .day:first").addClass("day_selected").select(); //Activate first tab

    // On Click Event
    $("div.days_panel .day").click(function() {

        $('.selected_plan').removeClass('selected_plan'); // remove selected plan on right panel

        $("div.days_panel .day_selected").removeClass("day_selected"); //Remove any "day_selected" class
        $(this).addClass("day_selected"); //Add "day_selected" class to selected tab
        
        // Call the callback function.
        on_day_change();
        
        return false;
    });
    
    // Left and right arrow click functions
    $('.left_day_arrow').click(function () {
        var current_offset = $('.day:first').attr('day_offset');
        if (current_offset != 0) {
            get_new_days(parseInt(current_offset) - 7);
        }
    });
    
    $('.right_day_arrow').click(function () {
        var current_offset = $('.day:first').attr('day_offset');
        get_new_days(parseInt(current_offset) + 7);
    });
}

// Callback function
function on_day_change() {
    show_data_container('#group_data');
    get_group_day_data();
    load_visible_plans()
}

// Gets and displays the set of days
function get_new_days(offset) {
    $.get('/home/get_weekday_tab_set', {
        starting_offset: 0
    }, function (data) {
        $('.seven_days').html(data);
        initialize_day_tab_rules();
    });
}