$(function () {
    initialize_day_tabs();
});

// Set up the day of the week tabs.
function initialize_day_tabs(day_index) {
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
        // Get previous week
        goto_day_offset(parseInt($('.day:first').attr('day_offset')) - 7);
    });
    
    $('.right_day_arrow').click(function () {
        // Get next week
        goto_day_offset(parseInt($('.day:first').attr('day_offset')) + 7);
    });
    
    // Select the corresponding (default first) day
    if (day_index == undefined) {
        day_index = 0;
    }
    $('.days_panel .day').eq(day_index).addClass('day_selected');
}

// Gets and displays the set of days
// Note that the current day of the week selection is preserved
function get_new_days(offset, day_index) {
    if (offset >= 0) {
        $.get('/home/get_weekday_tab_set', {
            starting_offset: offset
        }, function (data) {
            $('.seven_days').html(data);
            initialize_day_tabs(day_index);
            display_info();
        });
    }
}

// Seeks to the correct day tab and clicks the day
function goto_day_offset(offset) {
    if (offset < parseInt($('.day:first').attr('day_offset')) || offset > parseInt($('.day:last').attr('day_offset'))) {
        // Not in current seven days
        get_new_days(Math.floor(offset/7) * 7, offset % 7);
    } else  {
        // This week
        $('.day').eq(offset % 7).click();
    }
}