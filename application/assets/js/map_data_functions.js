$(function() {
    initialize_map_data_tabs();
})

// Initializes the map/data tabs.
function initialize_map_data_tabs() {
    // Initial select
    show_data_container('#group_data');
                
    // Click handler.
    $('div.tab_bar .data_tab').click(function (event_object) {
        if ($(this).hasClass('tab_selected')) {
            hide_data_containers();
        } else {
            $('div.tab_bar .data_tab').removeClass('tab_selected');
            show_data_container($(this).attr('assoc_div'))
        }
    });
}

// Get the data based on groups and the day from the server.
function get_group_day_data () {
    
    $.get('/home/get_group_day_data', {
        'selected_groups': get_selected_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
        // Replace the data and show the data tab.
        $('#group_data').html(data);
        $('#group_data').show('fast');
    });
}