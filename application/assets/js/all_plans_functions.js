$(function() {
    populate_visible_plans_panel(); 
});

// Populates the list of plans.
function populate_visible_plans_panel() {
    $.get('/home/get_visible_plans', {
        'selected_groups': get_selected_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
        alert(get_selected_groups());
       $('#visible_plans_panel').html(data); 
    });
}