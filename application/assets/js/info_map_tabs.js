$(function() {
    initialize_info_map_tabs(); 
})

var group_spinner = new Spinner(spinner_options());
var jqxhr;

// Initializes the map/data tabs.
function initialize_info_map_tabs() {
    // Click handler.
    $('div.tab_bar .data_tab').click(function () {
        if (!$(this).hasClass('tab_selected')) {
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
    $('#find_places').removeClass('selected');
    found_location = false;
}

// Returns true if at least one controll is selected
function controlls_are_selected() {
    return $('.selected_group, .network_active, .selected_location_tab, .selected_plan, .selected_friend_plan').length > 0 || found_location !== false;
}

// Displays information to the info box based on what's selected
var found_location = false;
var viewing_plan_location = false;
function display_info(bypass, arg) {
    // show the invite link and hide plan comments
    if(!$('.plan_content, .location_plan_content').hasClass('selected_plan') && $('.selected_location_tab').length == 0)
    {
        show_invite_link();
    }
    
    // Clear the change location box
    hide_change_location_panel(function(was_visible) {
        // Switch to the info tab if the change location box was visible
        if (was_visible) {
            show_data_container('#info_content');
        }
        
        if ($('#find_places.selected').length > 0) {    
            // Find a place
            
            show_invite_link();
        
            $.get('/home/show_place_search', function(data) {
                $('#info_content').html(data);
            
                // In field labels
                $('#info_content label').inFieldLabels();
            
                // Set up the autocomplete
                $('#search_for_places').autocomplete({
                    minLength: 2,
                    source: function(request, response) {
                        $.get('/home/find_places', {
                            needle: request.term,
                            latitude: myLatitude,
                            longitude: myLongitude
                        }, function(data) {
                            // Parse the JSON text.
                            data = $.parseJSON(data);
                
                            var response_json = ([]);
                            if (data.count > 0) {
                                response_json = $.map(data.data, function (item) {
                                    var label = item.name;
                                    if (item.category != null) {
                                        label += ' (' + item.category + ')';
                                    }
                                    label += ' - ' + parseFloat(item.distance).toFixed(2) + 'mi';
                                    return {
                                        'label': label,
                                        value: item.name,
                                        id: item.id
                                    };
                                });
                            }
                            response_json.push({
                                label: "Create place (it's easy!)", 
                                value: '', 
                                id: 'new place'
                            });
                        
                            response(response_json);
                        });
                    },
                    select: function(event, ui) {
                        deselect_all_controlls();
                    
                        if (ui.item.id == 'new place') {
                            // Open the plan panel and the new location modal
                            show_plan_modal(function() {
                                show_add_location_modal();
                            });
                        } else {
                            found_location = ui.item.id;
                    
                            display_info();
                        }
                    }
                });
            });
        } else if ($('.selected_plan, .selected_friend_plan').length > 0) {
            // Plan, friend's plan, or location's plan selected
        
            // setup spinner
            var plan_opts = spinner_options();
            var plan_target = document.getElementById('home_plan_spinner');
            var plan_spinner = new Spinner(plan_opts).spin(plan_target);
        
            // Load the selected plan
            $.get('/home/load_selected_plan_data', {
                'plan_selected': $('.selected_plan, .selected_friend_plan').attr('plan_id'),
                'friend_plan': $('.selected_friend_plan').length > 0
            }, function (data) {
                data = $.parseJSON(data);
            
                // Seek to the correct day
                goto_day_offset(data.data.date, true, function() {
                    var callback_func = function() {
                        // Populate the map
                        $.get('/home/get_plans_coords', {
                            plan_id: $('.selected_friend_plan, .selected_plan').attr('plan_id')
                        }, function(data) {
                            data = $.parseJSON(data);
                            populate_map(data, plan_marker_closure, true);
                        });
                    
                        // Setup the plan info
                        initialize_plan_info(data);
                    };
                    
                    if (bypass != true) {
                        // Load popular locations
                        populate_popular_locations(true, callback_func);
                    } else {
                        callback_func();
                    }
                });
            })
            .complete(function(){
                plan_spinner.stop(); // stop the spinner when the ajax call is finished
            });
        } else if ($('.selected_location_tab').length > 0 || Boolean(viewing_plan_location) || Boolean(found_location)) {
            // Location selected
        
            // setup spinner
            var opts = spinner_options();
            var target = document.getElementById('home_data_spinner');
            var location_spinner = new Spinner(opts).spin(target);
                
            // Get the correct place id and back button values
            var place_id;
            var back_to_plan = false;
            var back_to_search = false;
        
            if (found_location !== false) {
                place_id = found_location;
                back_to_search = true;
            } else if (viewing_plan_location === false) {
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
                'back_to_search': back_to_search,
                'back_to_groups': $('.selected_location_tab').length > 0
            }, function (data) {
                data = $.parseJSON(data);
            
                initialize_location_info(data);
                populate_map(data.map_data, selected_location_marker_closure, true);
            }).complete(function(){
                location_spinner.stop();
            });
            
            // Get the list of plans here
            $.get('/home/location_plans_made_here', {
                "place_id" : place_id
            },function(plans_data){
                $('.bottom_right_section, .comment_box, .plan_comments').hide('fast');
                $('#plans_made_here_list').html(plans_data);
                $('#plans_made_here').show('fast');
                
                // Click handler
                $('.location_plan_content').click(function () {
                    if (!$(this).hasClass('selected_plan')) {
                        // Deselect all controlls and show the info panel
                        //var selected_place = $('.selected_location_tab').attr('place_id');
                        deselect_all_controlls();
                        //$('.location_tab[place_id="' + selected_place + '"]').addClass('selected_location_tab');
                        $(this).addClass('selected_plan');
                        display_info(true);
                    }
                });
            });
        } else if ($('.network_active, .selected_group').length > 0) {
            // Network or group selected.
        
            // setup spinner
            var group_opts = spinner_options();
            var group_target = document.getElementById('home_data_spinner');
            group_spinner = new Spinner(group_opts).spin(group_target);
        
            // Make 'all' the default filter setting
            if(arg == undefined)
            {
                arg = 'all';
            }
    
            // display the information in the data box
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
            }).complete(function(){
                $('#view_group_list').click(function(){
                    populate_group_member_panel($('.selected_group').attr('group_id'));
                });            
            });
        
            // Load popular locations if necessary
            if (bypass != true) {
                populate_popular_locations();
            }else{
                // stop the spinner for a filter call (ie, "freshmen" or "sophomores" is selected)
                // right now it just stops immediately (without this code the spinner goes forever)
                jqxhr.complete(function(){
                    group_spinner.stop();
                });
            }
        } else {
            // No controlls selected
            $('#info_content').html('<img src="/application/assets/images/center_display.png" style="width:100%; height:100%;"><a href="/tutorial"><div class="tutorial_button">Tutorial</div></a>');
        
            // Load popular locations
            populate_popular_locations();
        }
    });
}

// Sets up the location view (graphs and whatnot)
// Used for viewing locations and friends' plans

function initialize_location_info(data) {
    // Apply the layout HTML
    $('#info_content').html(data.html);
    data = data.graph_data;
    
    // Populate the graphs
    populate_day_graph('.day_plan_graph', data.plan_dates, data.selected_date, 'location_graph_bar');
    two_percentage_bar('.two_percent_wrapper', data.percent_male, data.percent_female, 'two_bar_male', 'two_bar_female');
                
    // Make plan click handler
    $('.make_plan_here').click(function() {
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
    
    // View map
    $('.view_map').click(function() {
        show_data_container('#map_content'); 
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
    
    // Back to search click handler (not always visible)
    $('.back_to_search').click(function () {
        deselect_all_controlls();
        $('#find_places').click();
    });
}

// Sets up the plan info view
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
        }, function(data) {
            data = $.parseJSON(data);
                
            if (data.status != 'success') {
                // Plan conflict
                populate_plan_panel();
                open_conflict_modal(data, function() {
                    display_info();
                    populate_plan_panel();
                });
            } else {
                // Success
                populate_plan_panel();
                display_info();
            }
            
        });
    });
    
    // View attendees click handler
    $('#view_attendees').click(function(){
        $('.guest_list_button_selected').removeClass('guest_list_button_selected');
        $('.attending_button').addClass('guest_list_button_selected');
        $('#awaiting_reply').hide();
        $('#attending_modal_content').show();
        populate_plan_attending_panel();    
    });
}

// Populates the popular locations panel
function populate_popular_locations(skip_update_map, callback) {
    jqxhr = $.get('/home/load_location_tabs', {
        'selected_groups': get_selected_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
        data = $.parseJSON(data);
        
        // Populate the list
        $('.suggested_locations').html(data.html);
          
        // Places link click handler
        $('#places_link').click(function() {
            if ($('.selected_location_tab').length > 0) {
                $('.selected_location_tab').removeClass('selected_location_tab');
                display_info();
            }
            show_data_container('#map_content');
            return false;
        });
          
        // Location tab click handler
        $('.location_tab').click(function() {
            
            if(!$(this).hasClass('selected_location_tab'))
            {
                
                deselect_all_controlls();
            
                // Select this location tab
                $(this).addClass('selected_location_tab');
            } else {
                // Deselect this location tab
                $(this).removeClass('selected_location_tab');
                
                show_invite_link();
            }
        
            // Update the info box
            display_info();
            
        });
        
        // Populate the map
        if (skip_update_map == undefined) {
            populate_map(data.coords_array, location_marker_closure);
        }
        
        if (callback != undefined) {
            callback();
        }
    }).complete(function(){
        group_spinner.stop(); // stop the group spinner after the groups and locations are done
    });
}