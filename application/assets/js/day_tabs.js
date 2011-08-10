$(function () {
    initialize_day_tabs();
});

// Set up the day of the week tabs
function initialize_day_tabs() {
    day_click_handlers();

    // Highlight the first day
    $('.days_panel .day:first').addClass('day_selected');
}

// Seeks to the correct day tab and clicks the day
function goto_day_offset(offset, skip_click) {
    if (offset >= 0) {
        if (offset < parseInt($('.day:first').attr('day_offset')) || offset > parseInt($('.day:last').attr('day_offset'))) {
            // Not in current seven days
            $.get('/home/get_weekday_tab_set', {
                starting_offset: Math.floor(offset/7) * 7
            }, function (data) {
                // Replace the HTML
                $('.seven_days').html(data);
                
                day_click_handlers();
                
                // Select the appropriate day
                if (skip_click) {
                    $('.day[day_offset="' + offset + '"]').addClass('day_selected');
                } else {
                    $('.day[day_offset="' + offset + '"]').click();
                }
            });
        } else  {
            // This week
            if (skip_click) {
                $('.day_selected').removeClass('day_selected');
                $('.day').eq(offset % 7).addClass('day_selected');
            } else {
                $('.day').eq(offset % 7).click();
            }
        }
    } else {
        // Default to today
        if (skip_click) {
            $('.day_selected').removeClass('day_selected');
            $('.day:first').addClass('day_selected');
        } else {
            $('.day:first').click();
        }
    }
}

// Encapsulates the day click handler
function day_click_handlers() {
    // Click event
    $("div.days_panel .day").click(function() {
        if ($('.selected_plan').length > 0 && viewing_plan_location !== false) {
            // Remove any "day_selected" class
            $("div.days_panel .day_selected").removeClass("day_selected");
        
            // Add "day_selected" class to selected tab
            $(this).addClass("day_selected");
        
            // Select the current location if no other controlls are selected
            if (!controlls_are_selected()) {
                $('.network_tab[group_id="current_location"]').addClass('network_active');
            }
        
            // Display the info box
            display_info();
        }
    });
    
    // Left and right arrow click functions
    $('.days_panel .left_day_arrow').click(function () {
        // Get previous week
        goto_day_offset(parseInt($('.day_selected').attr('day_offset')) - 7);
    });
    
    $('.days_panel .right_day_arrow').click(function () {
        // Get next week
        goto_day_offset(parseInt($('.day_selected').attr('day_offset')) + 7);
    });
}