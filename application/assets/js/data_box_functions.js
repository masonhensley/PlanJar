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
        $('#group_data').html('<img src="/application/assets/images/help.png">');
    }
    load_visible_locations(selected_day, selected_groups); // update the popular locations shown    
    show_data_container('#group_data');
}

// updates the data box based on the selected groups
function load_data_box(selected_day, selected_groups)
{
    $.get('/home/load_data_box', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        // Apply the layout HTML
        $('#group_data').html(data);

        // Capture the data
        //data = data.data;
        
        // Populate the graphs
        populate_percentage_box('.total_percent_container', Math.random());
        populate_percentage_box('.male_percent_container', Math.random());
        populate_percentage_box('.female_percent_container', Math.random());
        populate_day_graph('.group_graph_bottom_middle', [{
            'date': '2011-07-28', 
            'count': 5
        },

        {
            'date': '2011-07-29', 
            'count': 1
        },

        {
            'date': '2011-07-30', 
            'count': 3
        },

        {
            'date': '2011-07-31', 
            'count': 7
        },

        {
            'date': '2011-08-01', 
            'count': 6
        },

        {
            'date': '2011-08-02', 
            'count': 2
        },

        {
            'date': '2011-08-03', 
            'count': 4
        }]);
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
    $('div.location_tab').click(function() {
        $('.event_tab_active').removeClass('event_tab_active');
        if(!$(this).hasClass('selected_location_tab'))
        {
            $('.selected_location_tab').removeClass('selected_location_tab');
            $(this).addClass('selected_location_tab');
            $.get('/home/show_location_data', {
                'place_id': $('.selected_location_tab').attr('place_id'),
                'date': $('.selected_location_tab').attr('date'),
                'selected_groups':get_selected_groups()
            }, function (data) {
                $('#location_data').html(data);
            });
        }
        show_data_container('#location_data');
    });
}