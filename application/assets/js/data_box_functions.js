$(function() {
    initialize_data_box();
});

var selected_day;
var selected_groups;

// this should be called whenever the groups or day selected changes
function update_groups_and_locations()
{
    selected_day = get_selected_day();
    selected_groups = get_selected_groups();
    
    load_data_box(selected_day, selected_groups); // update the data box to reflect selections
    load_visible_locations(selected_day, selected_groups); // delete all other instances of load_visible_plans
}

// updates for when 
function load_data_box(selected_day, selected_groups)
{
    $.get('/home/load_data_box', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        $('#group_data').html(data);
        $('#group_data').show('fast');
    });
}

// populates the popular location main panel
function load_visible_locations(selected_day, selected_groups){
    $.get('/home/load_popular_locations', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        $('.top_left_plans').html(data); 
    });
}