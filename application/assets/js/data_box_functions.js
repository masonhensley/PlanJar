// called when the DOM is loaded from "groups_panel_functions.js"
// this should be called whenever the groups or day selected changes
function update_groups_and_locations()
{
    selected_day = get_selected_day();
    selected_groups = get_selected_groups();
    
    if(selected_groups != null)
    {
        load_data_box(selected_day, selected_groups); // update the data box to reflect selections
    }else{
        $('#group_data').html('<img src="/application/assets/images/help.png"');
    }
    load_visible_locations(selected_day, selected_groups); // update the popular locations shown    
    show_data_container('#group_data');
}

// updates the data box based on the selected groups
function load_data_box(selected_day, selected_groups)
{
    $.get('/home/load_data_box_template', {
        'selected_groups': selected_groups,
        'selected_day': selected_day
    }, function (data) {
        
        $('#group_data').html(data);
        
        var graph_data = [1, 2, 3, 4, 5, 6, 7];
        var chart = d3.select(".graph_data");
        
        chart.enter()
        .append("div")
        .style("width", function(graph_data) {
            return graph_data * 10 + "px";
        }).style("height", "17px")
        .text(function(d) {
            return d;
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