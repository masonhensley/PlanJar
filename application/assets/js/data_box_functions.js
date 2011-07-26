// called when the DOM is loaded from "groups_panel_functions.js"
// this should be called whenever the groups or day selected changes
function update_groups_and_locations()
{
    selected_day = get_selected_day();
    selected_groups = get_selected_groups();
    
    load_data_box(selected_day, selected_groups); // update the data box to reflect selections
    load_visible_locations(selected_day, selected_groups); // update the popular locations shown
    load_upcoming_events(selected_groups); //update the upcoming events
}

// updates the data box based on the selected groups
function load_data_box(selected_day, selected_groups)
{
    $.get('/home/load_data_box', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        $('#group_data').html(data);
        show_data_container('#group_data');
    });
}

// populates the popular location main panel
function load_visible_locations(selected_day, selected_groups){
    $.get('/home/load_location_tabs', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        $('.suggested_locations').html(data); 
    });
}

function load_upcoming_events(selected_groups){
    $.get('/home/load_upcoming_events', {
        'selected_groups': selected_groups,
    }, function (data) {
        $('.upcoming_events').html(data); 
    });
}