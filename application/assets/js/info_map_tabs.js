$(function() {
    initialize_info_map_tabs();
})

// Initializes the map/data tabs.
function initialize_info_map_tabs() {
    // Click handler.
    $('div.tab_bar .data_tab').click(function () {
        if ($(this).hasClass('tab_selected')) {
            // Hide data container
            $('.tab_bar .data_tab').removeClass('tab_selected');
            $('.data_container:visible').hide('slide', {}, 'fast', function () {
                $('.data_container_wrapper').hide('blind', {}, 'fast');
            });
        } else {
            show_data_container($(this).attr('assoc_div'));
        }
    });
    
    display_info();
}

// Deselcts all controlls
function deselect_all_controlls(bypass_groups) {
    if (bypass_groups != true) {
        $('.selected_group').removeClass('selected_group');
    }
    $('.network_active').removeClass('network_active');
    $('.selected_location_tab').removeClass('selected_location_tab');
    $('.selected_plan').removeClass('selected_plan');
    $('.selected_friend_plan').removeClass('selected_friend_plan');
    viewing_plan_location = false;
}

// Returns true if at least one controll is selected
function controlls_are_selected() {
    return $('.selected_group, .network_active, .selected_location_tab, .selected_plan, .selected_friend_plan').length > 0;
}

// Displays information to the info box based on what's selected
function display_info(bypass, arg) {
    
    if ($('.selected_location_tab').length > 0 || viewing_plan_location !== false) {
        // Location selected
        
        // Get the correct place id and back button value
        var place_id;
        var back_to_plan = false;
        if (viewing_plan_location === false) {
            place_id = $('.selected_location_tab').attr('place_id');
        } else {
            place_id = viewing_plan_location;
            back_to_plan = true;
        }
        
        $.get('/home/show_location_data', {
            'place_id': place_id,
            'date': get_selected_day(),
            'selected_groups': get_selected_groups(),
            'back_to_plan': back_to_plan,
            'back_to_groups': $('.selected_location_tab').length > 0
        }, function (data) {
            initialize_location_info(data);
        });
    } else if ($('.network_active, .selected_group').length > 0) {
        // Network or group selected.
        // setup spinner
        
        var target = document.getElementById('suggest_people');
        var spinner = new Spinner(spinner_options()).spin(target);
        
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
            $('#filter').val(arg);
        
            // Populate the graphs
            populate_percentage_box('.total_percent_container', data.percent_total_going_out, 'percent_bar_total');
            populate_percentage_box('.male_percent_container', data.percent_males_going_out, 'percent_bar_male');
            populate_percentage_box('.female_percent_container', data.percent_females_going_out, 'percent_bar_female');
            populate_day_graph('.group_graph_top_right', data.plan_dates, data.selected_date, 'network_graph_bar');
        
            // Reload the display info when the filter select is changed
            $('#filter').change(function(){
                display_info(true, $(this).val());
            });
            
            
        });
        
        // Load popular locations if necessary
        if (bypass != true) {
            populate_popular_locations();
        }
        
        //stop spinner
        spinner.stop();
        
    } else if ($('.selected_plan, .selected_friend_plan').length > 0) {
        // Plan or friend's plan selected
        
        // Load the selected plan
        $.get('/home/load_selected_plan_data', {
            'plan_selected': $('.selected_plan, .selected_friend_plan').attr('plan_id'),
            'friend_plan': $('.selected_friend_plan').length > 0
        }, function (data) {
            data = $.parseJSON(data);
            
            // Seek to the correct day
            goto_day_offset(data.data.date, true, function() {
                // Load popular locations
                populate_popular_locations(true, function() {
                    // Populate the map
                    $.get('/home/get_plans_coords', {
                        plan_id: $('.selected_friend_plan, .selected_plan').attr('plan_id')
                    }, function(data) {
                        data = $.parseJSON(data);
                
                        populate_map(data, plan_marker_closure, true);
                    });
                    
                    // Setup the plan info
                    initialize_plan_info(data);
                });
            });
        });
    } else {
        // No controlls selected
        $('#info_content').html('<img src="/application/assets/images/center_display.png" style="width:100%; height:100%;">');
        
        // Load popular locations
        populate_popular_locations();
    }
}

// Sets up the location view (graphs and whatnot)
// Used for viewing locations and friends' plans
function initialize_location_info(data) {
    data = $.parseJSON(data);

    // Apply the layout HTML
    $('#info_content').html(data.html);
    data = data.graph_data;
    
    // Populate the graphs
    populate_day_graph('.day_plan_graph', data.plan_dates, data.selected_date, 'location_graph_bar');
    two_percentage_bar('.two_percent_wrapper', data.percent_male, data.percent_female, 'two_bar_male', 'two_bar_female');
                
    // Make plan click handler
    $('.make_plan').click(function() {
        show_plan_modal(function () {
            // Pre-populate the place name and id
            $('#plan_location').val(data.place_name);
            $('#plan_location_id').val(data.place_id);
            
            // Select the correct day
            goto_plan_day_offset(parseInt($('.day_selected').attr('day_offset')), function () {
                $('.plan_day[day_offset="' + $('.day_selected').attr('day_offset') + '"]').click();
            });
        });
    });
    
    // Back to plan click handler (not always visible)
    $('.back_to_plan').click(function () {
        viewing_plan_location = false;
        display_info();
    });
    
    // Back to groups click handler (not always visible)
    $('.back_to_groups').click(function () {
        // Deselect the group and update the display
        $('.selected_location_tab').removeClass('selected_location_tab');
        display_info();
    });
}

// Sets up the plan info view
var viewing_plan_location = false;
function initialize_plan_info(data) {
    // Replace the data and show the data tab.
    $('#info_content').html(data.html);
    data = data.data;
        
    // Initialize the graphs
    two_percentage_bar('.plan_gender_graph', data.percent_male, data.percent_female, 'two_bar_male', 'two_bar_female');
    populate_percentage_box('.attending_graph', data.percent_attending, 'percent_bar_total');
        
    // Handles clicking on the delete plan button
    $('.delete_plan').confirmDiv(function (clicked_elem) {
        $.get('/home/delete_plan', {
            'plan_selected': $('.selected_plan').attr('plan_id')
        }, function (data) {
            // Replace the data and show the info tab.
            $('#info_content').html(data);
                    
            populate_plan_panel();
        });
                
        // Display the info box after the plan tabs HTML has been replaced
        populate_plan_panel(function () {
            display_info();
        });
    });
        
    // Handles clicking on invite people
    $('.invite_people').click(function () {
        open_invite_modal('event', data.event_id, data.privacy, data.originator);
    });
            
    // Handles clicking on the see place button
    $('.view_plan_location').click(function () {
        // Save the place id to allow for day tab navigation
        viewing_plan_location = data.location_id;
        
        // Seek to the correct day
        goto_day_offset(data.date, true, function () {
            display_info();
        });
    });
    
    // Handles clicking on the make plan button
    $('.make_plan').click(function() {
        $.get('/home/make_plan_by_event', {
            'event_id': data.event_id,
            'privacy': data.privacy
        }, function() {
            populate_plan_panel();
            display_info();
        });
    });
    
    // View attendees click handler
    $('#view_attendees').click(function(){
        $.get('/home/attending_list', {
            plan_id : $('#view_attendees').attr('plan_id')
        }, function(data){
            // Make it draggable (with a handle).
            $('#plan_attending_panel').draggable({
                handle: '.title_bar'
            }); 
            $('#plan_attending_panel').html(data);
            $('#plan_attending_panel').show('fast');
            // Closing click handler
            $('#cancel_friends_panel').click(function () {
                $('#plan_attending_panel').hide('fast');
            });
        });    
    });
}

// Populates the popular locations panel
function populate_popular_locations(skip_update_map, callback) {
    $.get('/home/load_location_tabs', {
        'selected_groups': get_selected_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
        data = $.parseJSON(data);
        
        // Populate the list
        $('.suggested_locations').html(data.html);
            
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
        
        // Populate the map
        if (skip_update_map == undefined) {
            populate_map(data.coords_array, location_marker_closure);
        }
    });
    
    if (callback != undefined) {
        callback();
    }
}