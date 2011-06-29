$(function() {
    initialize_map_data_tabs();
})

// Initializes the map/data tabs.
function initialize_map_data_tabs() {
    // Initial select
    $('div.tab_bar ').addClass('tab_selected');
    $($('#map_data_tabs li:first').attr('assoc_div')).show('fast');
                
    // Click handler.
    $('#map_data_tabs li').click(function (event_object) {
        if ($(this).hasClass('tab_selected')) {
            $(this).removeClass('tab_selected');
            $('div.map_data_content').hide('fast');
        } else {
            $('#map_data_tabs li').removeClass('tab_selected');
            $(this).addClass('tab_selected');
            $('div.map_data_content').hide('fast');
            $($(this).attr('assoc_div')).show('fast');
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
        $('#data_tab').html(data);
        if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
            $("#map_data_tabs").tabs('select', '#data_tab');
        }
    });
}