$(function() {
    initialize_map_data_tabs();
})

// Initializes the map/data tabs.
function initialize_map_data_tabs() {
    // Initial select
    show_data_container('#goup_data');
                
    // Click handler.
    $('div.tab_bar .data_tab').click(function (event_object) {
        if ($(this).hasClass('tab_selected')) {
            $(this).removeClass('tab_selected');
            $('div.data_container').hide('fast');
        } else {
            $('div.tab_bar .data_tab').removeClass('tab_selected');
            $(this).addClass('tab_selected');
            $('div.data_container').hide('fast');
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
        $('#group_data').html(data);
        $('#group_data').show('fast');
    });
}