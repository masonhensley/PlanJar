$(function () {
    initialize_day_tabs();
});

// Set up the day of the week tabs.
function initialize_day_tabs(initial_offset) {
    // initial_offset default of 0
    if (initial_offset == undefined) {
        initial_offset = 0;
    }
    
    // Click event
    $("div.days_panel .day").click(function() {
        // Remove any "day_selected" class
        $("div.days_panel .day_selected").removeClass("day_selected");
        
        // Add "day_selected" class to selected tab
        $(this).addClass("day_selected");
        
        // Select the current location if no other controlls are selected
        if (!controlls_are_selected()) {
            $('.network_tab[group_id="current_location"]').addClass('network_active');
        }
        
        // Display the info box
        display_info(true);
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
    
    // Select the corresponding (default first) day
    $('.days_panel .day').eq(initial_offset).addClass('day_selected');
}

// Gets and displays the set of days
function get_new_days(offset) {
    var current_eq = $('.day_selected').index();
    $.get('/home/get_weekday_tab_set', {
        starting_offset: offset
    }, function (data) {
        $('.seven_days').html(data);
        initialize_day_tabs(current_eq);
        display_info();
    });
}