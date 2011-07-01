$(function() {
    setup_day_tabs();
});

// Initializes the day of the week tabs.
function setup_day_tabs() {
    // Populate the initial days.
    $.get('/home/get_weekday_tab_set', {
        starting_offset: 0
    }, function (data) {
        $('.seven_days').html(data);
        
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
    });
    
    // Left and right arrows
    $('.left_day_arrow').click(function () {
        var current_offset = $('.day:first').attr('day_offset');
        if (current_offset != 0) {
            $.get('/home/get_weekday_tab_set', {
                starting_offset: current_offset - 7
            }, function(data) {
                $('.seven_days').html(data);
            });
        }
    });
    $('.right_day_arrow').click(function () {
        var current_offset = $('.day:first').attr('day_offset');
        $.get('/home/get_weekday_tab_set', {
            starting_offset: current_offset + 7
        }, function(data) {
            $('.seven_days').html(data);
        });
    });
}

// Callback function
function on_day_change() {
    show_data_container('#group_data');
    get_group_day_data();
    load_visible_plans()
}