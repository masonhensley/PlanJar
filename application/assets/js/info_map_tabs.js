$(function() {
    initialize_data_tabs();
})

// Initializes the map/data tabs.
function initialize_data_tabs() {
    // Click handler.
    $('div.tab_bar .data_tab').click(function () {
        if ($(this).hasClass('tab_selected')) {
            hide_data_containers();
        } else {
            show_data_container($(this).attr('assoc_div'));
        }
    });
}

// Deselcts all controlls
function deselect_all_controlls() {
    $('.selected_group').removeClass('selected_group');
    $('.network_active').removeClass('network_active');
    $('.selected_location_tab').removeClass('selected_location_tab');
    $('.selected_plan').removeClass('selected_plan');
}

// Displays information to the info box based on what's selected
function display_info(arg) {
    if ($('.selected_location_tab').length > 0) {
        // Location selected
        $.get('/home/show_location_data', {
            'place_id': $('.selected_location_tab').attr('place_id'),
            'date': $('.selected_location_tab').attr('date'),
            'selected_groups':get_selected_groups()
        }, function (data) {
            // Parse the JSON
            data = $.parseJSON(data);
                
            // Apply the layout HTML
            $('#info_content').html(data.html);
                
            // Capture the data
            data = data.graph_data;
                
            // Populate the graphs
            populate_day_graph('.day_plan_graph', data.plan_dates, 'today');
            two_percentage_bar('.two_percent_wrapper', data.percent_male, data.percent_female, 'two_bar_male', 'two_bar_female');
                
            // Show the group data tab
            show_data_container('#info_content');
        });
    } else if ($('.network_active, .selected_group').length > 0) {
        // Network or group selected.
        
        // Make 'all' the default filter setting
        if(arg == undefined)
        {
            arg = 'all';
        }
    
        $.get('/home/load_data_box', {
            'selected_groups': get_selected_groups(),
            'selected_day': get_selected_day(),
            'filter': arg
        }, function (data) {
            // Parse the JSON
            data = $.parseJSON(data);
        
            // Apply the layout HTML
            $('#info_content').html(data.html);

            // Capture the data
            data = data.data;
        
            // Select the correct value for the select box
            $('#filter').val(data['filter']);
        
            // Populate the graphs
            populate_percentage_box('.total_percent_container', data.percent_total_going_out, 'percent_bar_total');
            populate_percentage_box('.male_percent_container', data.percent_males_going_out, 'percent_bar_male');
            populate_percentage_box('.female_percent_container', data.percent_females_going_out, 'percent_bar_female');
            populate_day_graph('.group_graph_top_right', data.plan_dates, data.selected_date);
        
            $('#filter').change(function(){
                display_info($(this).val());
            });
        });
        
        // Populates the popular locations panel
        $.get('/home/load_location_tabs', {
            'selected_groups': get_selected_groups(),
            'selected_day': get_selected_day()
        }, function (data) {
            $('.suggested_locations').html(data); 
            
            // Location tab click handler
            $('div.location_tab').click(function() {
                if(!$(this).hasClass('selected_location_tab'))
                {
                    // Deselect selected location tabs
                    $('.selected_location_tab').removeClass('selected_location_tab');
            
                    // Select this location tab
                    $(this).addClass('selected_location_tab');
                } else {
                    // Deselect this location tab
                    $(this).removeClass('selected_location_tab');
                }
        
                // Update the info box
                display_info();
            });
        });
    } else if ($('.selected_plan').length > 0) {
        // Plan selected
        $.get('/home/load_selected_plan_data', {
            'plan_selected': $('.selected_plan').attr('plan_id')
        }, function (data) {
            data = $.parseJSON(data);
        
            // Replace the data and show the data tab.
            $('#info_content').html(data.html);
        
            // Handles clicking on the delete plan button
            $('.delete_plan').confirmDiv(function (clicked_elem) {
                $.get('/home/delete_plan', {
                    'plan_selected': $('.selected_plan').attr('plan_id')
                }, function (data) {
                    // Replace the data and show the data tab.
                    $('#info_content').html(data);
                    populate_plan_panel();
                });
            });
        
            // Handles clicking on invite people
            $('.invite_people').click(function () {
                open_invite_modal('event', data.event_id, data.privacy, data.originator);
            });
        });
    } else {
        // No controlls selected
        $('#info_content').html('<img src="/application/assets/images/center_display.png">');
    }
}