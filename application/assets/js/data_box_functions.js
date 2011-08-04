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
function load_data_box(filter)
{
    
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
}