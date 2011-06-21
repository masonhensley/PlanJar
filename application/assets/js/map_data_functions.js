$(function() {
    initialize_map_data_tabs();
})

// Initializes the map/data tabs.
function initialize_map_data_tabs() {
    $("#map_data_tabs").tabs({
        collapsible: true,
        fx: {
            opacity: 'toggle',
            duration: 'slow'
        }
    });

    // Make the tabs go on the bottom.
    $("#map_data_tabs .tabs-bottom .ui-tabs-nav, #map_data_tabs .tabs-bottom .ui-tabs-nav > *")
    .removeClass("ui-corner-all ui-corner-top")
    .addClass("ui-corner-bottom");
}

// Get the data based on groups and the day from the server.
function get_group_day_data () {
    $.get('/home/get_group_day_data', {
        'selected_groups': get_selected_groups(),
        'selected_day': $('#day_tabs .day_selected a').attr('href')
    }, function (data) {
        // Replace the data and show the data tab.
        $('#data_tab').html(data)
        if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
            $("#map_data_tabs").tabs('select', '#data_tab');
        }
    });
}