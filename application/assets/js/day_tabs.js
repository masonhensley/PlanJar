$(function () {
    initialize_day_tabs();
});

// Set up the day of the week tabs.
function initialize_day_tabs() {
    // Click event
    $("div.days_panel .day").click(function() {
        // Remove any "day_selected" class
        $("div.days_panel .day_selected").removeClass("day_selected");
        
        // Add "day_selected" class to selected tab
        $(this).addClass("day_selected");
        
        // Remove any selected location
        $('.selected_location_tab').removeClass('selected_location_tab');
        
        // Select the school network if no groups are selected
        if (get_selected_groups().length == 0) {
            $('.network_tab[group_id="school"]').click();
        }
        
        // Display the info box
        display_info();
    });
    
    $("div.days_panel .day:first").click(); //Activate first tab
    
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

// Gets and displays the set of days
function get_new_days(offset) {
    $.get('/home/get_weekday_tab_set', {
        starting_offset: offset
    }, function (data) {
        $('.seven_days').html(data);
        initialize_day_tabs();
    });
}