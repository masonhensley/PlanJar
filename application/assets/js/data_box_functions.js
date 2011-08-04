// called when the DOM is loaded from "groups_panel_functions.js"
// this should be called whenever the groups or day selected changes
function update_groups_and_locations()
{
    selected_day = get_selected_day();
    selected_groups = get_selected_groups();
    
    if(selected_groups.length != 0)
    {
        load_data_box(selected_day, selected_groups); // update the data box to reflect selections
    }else{
        $('#info_content').html('<img src="/application/assets/images/center_display.png">');
    }
    load_visible_locations(selected_day, selected_groups); // update the popular locations shown    
    show_data_container('#info_content');
}

// updates the data box based on the selected groups
function load_data_box(selected_day, selected_groups, filter)
{
    
    if(filter == undefined)
    {
        filter = 'all';
    }
    
    $.get('/home/load_data_box', {
        'selected_groups': selected_groups,
        'selected_day': selected_day,
        'filter': filter
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
            load_data_box(selected_day, selected_groups, $(this).val());
        });
    });
}

// populates the popular location main panel
function load_visible_locations(selected_day, selected_groups){
    $.get('/home/load_location_tabs', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        $('.suggested_locations').html(data); 
        show_selected_location();
    });
}



function show_selected_location() {
    // Location tab click handler
    $('div.location_tab').click(function() {
        if(!$(this).hasClass('selected_location_tab'))
        {
            // Deselect all controlls
            deselect_all_controlls();
            
            // Select this location tab
            $(this).addClass('selected_location_tab');
            
            
            $.get('/home/show_location_data', {
                'place_id': $('.selected_location_tab').attr('place_id'),
                'date': $('.selected_location_tab').attr('date'),
                'selected_groups':get_selected_groups()
            }, function (data) {
                // Parse the JSON
                data = $.parseJSON(data);
                console.log(data);
                
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
        } else {
            // No controlls selected
            $('#info_content').html('<img src="/application/assets/images/center_display.png">');
        }
    });
}