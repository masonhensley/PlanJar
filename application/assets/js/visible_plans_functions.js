$(function() {
    populate_visible_plans_panel(); 
});

// Populates the list of plans every time a weekday or group is selected
function populate_visible_plans_panel() {
    $.get('/home/load_popular_locations', {
        'selected_groups': get_selected_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
       $('#visible_plans_panel').html(data); 
    });
}