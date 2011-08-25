$(function() {
    initialize_plan_modal();
});

// Shows the plan modal
// Executes callback after the modal opens
function show_plan_modal(callback) {
    // Add the city name to the handle text.
    $('#create_plan_content .title_bar .text').html('Make a plan');
        
    // Select everything when focused
    $('#plan_location').click(function () {
        $(this).focus();
        $(this).select();
    });
    
    // Show the modal
    $('#create_plan_content').show('fast', function () {
        // Focus the location box.
        $('#plan_location').focus();
        
        if (callback != undefined) {
            callback();
        }
    });
}

// Initializes the plan modal
function initialize_plan_modal() {
    // Opening click handler
    $('#create_plan').click(function () {
        show_plan_modal();
    });
    
    // Closing click handler
    $('#cancel_plan').click(function () {
        $('#create_plan_content').hide('fast', function () {
            // Clear the plan modal
            reset_plan_modal();
        });
    });
    
    // Make it draggable (with a handle).
    $('#create_plan_content').draggable({
        handle: '.title_bar'
    });
    
    // Initialize the plan location autocomplete instance.
    initialize_plan_autocomplete();
    
    // Divsets
    $('#plan_time, #plan_privacy_wrapper, #plan_day').divSet();
    
    // Additional day/time click handler
    $('#plan_day div, #plan_time div').click(function() {
        populate_selectable_events();
        
        toggle_time_day_buttons();
    });
    
    // Plan time click handler
    $('#plan_time .divset').click(function () {
        // Clear and blur the time box
        $('#plan_clock_time').val('');
        $('#plan_clock_time').blur();
    });
    
    // Left and right day click handlers
    $('#plan_place_time_wrapper .left_day_arrow').click(function() {
        $(this).removeClass('divset_selected');
        goto_plan_day_offset(parseInt($('.plan_day:first').attr('day_offset')) - 7);
    });
    $('#plan_place_time_wrapper .right_day_arrow').click(function() {
        $(this).removeClass('divset_selected');
        goto_plan_day_offset(parseInt($('.plan_day:first').attr('day_offset')) + 7);
    });
    
    // In-field labels
    $('#create_plan_content .in-field_block label').inFieldLabels();
    
    // Time entry
    $('#plan_clock_time').timeEntry({
        spinnerImage: '',
        ampmPrefix: ' ',
        ampmNames: ['am', 'pm'],
        defaultTime: '12:00 pm'
    });
    
    // Clock time keyup handler
    $('#plan_clock_time').keyup(function() {
        // Select the appropriate time of day
        var date = Date.parse($(this).val());
        if (date != null) {
            var hours = date.getHours();
            var time_to_select;
            if (hours >= 18 && hours <= 22) {
                time_to_select = 'night';
            } else if (hours >= 12 && hours < 18) {
                time_to_select = 'afternoon';
            } else if (hours >= 5 && hours < 12) {
                time_to_select = 'morning';
            } else {
                time_to_select = 'late_night';
            }
            
            $('#plan_time .divset').removeClass('divset_selected');
            $('#plan_time .divset[plan_time="' + time_to_select + '"]').addClass('divset_selected');
            
            if (plan_time_place_valid()) {
                $('#plan_place_location_buttons').show('fast');
            }
            
            populate_selectable_events();
        }
    });
    
    // Create event click handler
    $('#create_event').click(function () {
        // Load the selectable events
        populate_selectable_events();
        
        // Show and hide the necessary components
        $('#create_event, #submit_plan').hide('fast');
        $('#plan_events_wrapper, #plan_warning_message').show('fast');
    });
    
    // Event title change handler
    $('#event_title').keyup(function() {
        if ($(this).val().length != '') {
            // Deselect any selected event
            $('.selected_event').removeClass('selected_event');
                
            // Show and hide the necessary components
            $('#plan_privacy_wrapper, #add_plan_description, #submit_plan').show('fast');
            $('#plan_warning_message').hide('fast');
        } else {
            // Hide and reset the privacy
            $('#plan_privacy_wrapper').hide('fast', function () {
                $('#plan_privacy_wrapper div:first').click();
            });
                
            // Hide and reset the description
            $('#plan_description_wrapper').hide('fast', function() {
                $('#plan_description').val('');
                $('#plan_description').blur();
            });
                
            // Hide the buttons
            $('#add_plan_description, #submit_plan').hide('fast');
                
            // Show the warning
            $('#plan_warning_message').show('fast');
        }
    });
    
    // Initial privacy select
    $('#plan_privacy_wrapper div:first').click();
    
    // Add description click handler
    $('#add_plan_description').click(function() {
        // Show and hide the necessary components
        $('#add_plan_description, #submit_plan').hide('fast');
        $('#plan_warning_message, #plan_description_wrapper').show('fast');
    });
    
    // Description change handler
    $('#plan_description').keyup(function() {
        if ($(this).val() != '') {
            $('#plan_warning_message').hide('fast');
            $('#submit_plan').show('fast');
        } else {
            $('#submit_plan').hide('fast');
            $('#plan_warning_message').show('fast');
        }
    })
    
    // Submit
    $('#submit_plan').click(function () {
        //Make sure an event is selected or an event has been created
        if ($('.selected_event').length > 0 || $('#event_title').val().length > 0) {
            submit_plan();
        }
    });
}

// Returns true if the plan location and time are valid
function plan_time_place_valid() {
    return $('#plan_location_id').val() != '' && $('.plan_day.divset_selected, #plan_time .divset_selected').length > 1
}

// Shows/hides the necessary buttons for the plan time/place/location section
function toggle_time_day_buttons() {
    if (plan_time_place_valid() && !$('#plan_events_wrapper').is(':visible')) {
        if (!$('#plan_event_select_wrapper').is(':visible')) {
            // Show the necessary buttons
            $('#create_event, #submit_plan').show('fast');
        }
            
        // Hide the warning
        $('#plan_warning_message').hide('fast');
    }
}

// Shows/hides the necessary buttons for the plan event section
function toggle_event_buttons() {
    if (plan_time_place_valid() && !$('#plan_description_wrapper').is(':visible')) {
        if (!$('#plan_event_select_wrapper').is(':visible')) {
            // Show the necessary buttons
            $('#create_event, #submit_plan').show('fast');
        }
            
        // Hide the warning
        $('#plan_warning_message').hide('fast');
    }
}

// Populates the selectable events and initializes the click handlers
function populate_selectable_events() {
    if (plan_time_place_valid()) {
        // Populate the event select
        $.get('/home/get_events_for_plan', {
            day: $('#plan_day .divset_selected').attr('day_offset'),
            time: $('#plan_time .divset_selected').attr('plan_time'),
            place_id: $('#plan_location_id').val()
        }, function (data) {
            $('#plan_event_select_wrapper').html(data);
        
            // Event select click handler
            $('.selectable_event').click('click', function () {
                // Make only this selected
                $(this).siblings().removeClass('selected_event');
                $(this).addClass('selected_event');
        
                // Store the selected event id
                $('#plan_event_id').val($(this).attr('event_id'));
                
                // Clear the event
                $('#event_title').val('');
                $('#event_title').blur();
                $('#plan_privacy_wrapper').hide('fast');
                $('#plan_privacy_wrapper div:first').click();
                
                // Hide and reset the description
                $('#plan_description_wrapper').hide('fast', function() {
                    $('#plan_description').val('');
                    $('#plan_description').blur();
                });
                
                // Show and hide the necessary controls
                $('#plan_warning_message, #add_plan_description').hide('fast');
                $('#submit_plan').show('fast');
            });
        });
    }
}

// Seeks to the corresponding week
function goto_plan_day_offset(offset, callback) {
    if (offset >= 0) {
        if (offset < parseInt($('.plan_day:first').attr('day_offset')) || offset > parseInt($('.plan_day:last').attr('day_offset'))) {
            // Not in current seven days
            $.get('/home/get_weekday_tab_set', {
                starting_offset: Math.floor(offset/7) * 7,
                plan_set: true
            }, function (data) {
                // Replace the HTML
                $('#plan_day').html(data);
                
                $('#plan_day').divSet();
                
                if (callback != undefined) {
                    callback();
                }
            });
        } else if (callback != undefined) {
            callback();
        }
    }
}

// Submits the plan and closes the window (also opens the invite window)
// from_just_go should be set if this function is called from the "just go" button
function submit_plan(from_just_go) {
    // Make sure a new event name isn't already taken
    if ($('#event_title').val() != '') {
        $.get('/home/check_preexisting_event', {
            needle: $('#event_title').val(),
            'plan_time': $('#plan_time .divset_selected').attr('plan_time'),
            'plan_day': $('.plan_day.divset_selected').attr('day_offset'),
            'place_id': $('#plan_location_id').val()
        }, function (data) {
            if (data != 'available') {
                // Alert the error message from the server
                alert(data);
                
                // Focus and select the event title
                $('#event_title').focus();
                $('#event_title').select();
            } else {
                submit_plan_helper(from_just_go);
            }
        });
    } else {
        submit_plan_helper(from_just_go)
    }
}

// Encapsulates some of the submit code so it can be run from multiple locations in the submit function
function submit_plan_helper(from_just_go) {
    // Get the privacy setting from either the divSet or the <select>
    var privacy;
    if (from_just_go) {
        // Plan submitted by clicking on just go. Use open privacy
        privacy = 'open';
    } else {
        // Plan submitted normally
        if ($('#plan_event_select_wrapper .selected_event').length == 0) {
            privacy = $('#plan_privacy_wrapper .divset_selected').attr('priv_val');
        } else {
            privacy = $('#plan_event_select_wrapper .selected_event').attr('priv_type');
        }
    }
        
    $.get('/home/submit_plan?' + $('#plan_form').serialize(), {
        'plan_time': $('#plan_time .divset_selected').attr('plan_time'),
        'plan_day': $('.plan_day.divset_selected').attr('day_offset'),
        'privacy': privacy
    } ,function (data) {
        // Hide and reset the modal
        $('#create_plan_content').hide('fast', function () {
            // Clear the plan modal
            reset_plan_modal();
            
            open_conflict_invite(data, privacy);
        });
                
        // Refresh the plan list.
        populate_plan_panel();
    });
}

// Opens the invite and/or conflict panel based on the given data
function open_conflict_invite(data, privacy) {
    data = $.parseJSON(data);
              
    if (data.status == 'success') {
        if (privacy != 'strict' || data.originator) {
            // Open the invite modal
            open_invite_modal('event', data.event_id, privacy, data.originator);
        }
    } else {
        // Open the conflict modal
        open_conflict_modal(data, function (resulting_privacy, originator, event_id) {
            // Refresh the plan panel
            populate_plan_panel();
            
            if (resulting_privacy != 'strict' || originator) {
                // Invite people
                open_invite_modal('event', event_id, resulting_privacy, originator);
            }
        });
    }
}

// Returns a string with the plan description (place and day/time)
function generate_plan_text() {
    var day = $('.plan_day.divset_selected').html();
    var time = $('#plan_time .divset_selected').html().toLowerCase();
    var return_string = '<b>' + $('#plan_location_name').val() + '</b><br/>';
    
    if (day == 'Today') {
        // Today
        if (time == 'morning' || time == 'afternoon') {
            return_string +=  'this ' + time;
        } else if (time == 'night') {
            return_string += 'tonight'
        } else {
            return_string += 'late night tonight';
        }
    } else {
        // Any other day
        if (time == 'late night') {
            return_string += 'late into the night'
        } else {
            return_string += 'the ' + time;
        }
        return_string += ' of <b>' + day + '</b>';
    }
    
    return return_string;
}

// Moves to the next plan panel
function next_plan_panel() {
    if ($('.plan_page_content:first:visible').length == 1) {
        // The first panel is visible. Don't continue unless a place was selected and a time is selected
        if ($('#plan_location_id').val() != '' && $('.plan_day.divset_selected').length == 1 && ($('#plan_time .divset_selected').length == 1 || $('#plan_clock_time').val() != '')) {
            // Hide the first panel and show the second
            $('.plan_page_content:first').hide('slide', {}, 'fast', function () {
                $('.plan_page_content:eq(1)').show('slide', {
                    direction: 'right'
                }, 'fast');
            });
            
            // Initialize the next page
            initialize_event_select_page();
        }
    }
}

// Moves to the previous panel
function prev_plan_panel() {
    if ($('.plan_page_content:eq(1):visible').length == 1) {
        // The second panel is visible. Hide the second panel and show the first panel
        $('.plan_page_content:eq(1)').hide('slide', {
            direction: 'right'
        }, 'fast', function () {
            $('.plan_page_content:first').show('slide', {}, 'fast');
        });
    }
}

// Returns the distance between the two locations in miles
function get_distance_between(lat0, long0, lat1, long1) {
    return ((Math.acos(Math.sin(lat0 * Math.PI / 180) * Math.sin(lat1 * Math.PI / 180) 
        + Math.cos(lat0 * Math.PI / 180) * Math.cos(lat1 * Math.PI / 180) * Math.cos((long0 - long1)
            * Math.PI / 180)) * 180 / Math.PI) * 60 * 1.1515);
}

// Resets and clears the modal
function reset_plan_modal() {
    // Clear all inputs
    $('#create_plan_content input').not('[type="button"]').val('');
    $('#plan_description').val('');
    
    // Clear the divsets
    $('#create_plan_content .divset_selected').removeClass('divset_selected');
    
    // Select the privacy divset
    $('#plan_privacy_wrapper div:first').addClass('divset_selected');
    
    // Clear the event select
    $('#plan_event_select_wrapper').html('');
    
    // Blur necessary inputs
    $('#event_title, #event_description').blur();
    
    // Hide everything
    $('#plan_events_wrapper, #plan_privacy_wrapper, #plan_description_wrapper').css('display', 'none');
    
    // Show the hidden buttons
    $('#plan_place_location_buttons, #add_plan_description').css('display', '');
}

// Encapsulates the autocomplete setup
function initialize_plan_autocomplete() {
    var item_selected;
    $('#plan_location').autocomplete({
        minLength: 2,
        source: function (request, response) {
            // Get places from the PlanJar server.
            $.get('/home/find_places', {
                needle: request.term,
                latitude: myLatitude,
                longitude: myLongitude
            }, function (data) {
                // Keep track of whether an item was selected or not (delayed autocomplete items fix).
                item_selected = false;
                
                // Parse the JSON text.
                data = $.parseJSON(data);
                    
                var place_count = data.count;
                var place_limit = 10 - place_count;
                    
                // We're done with count, so overwrite data with data.data (Peter Griffin laugh).
                data = data.data;
                    
                // Set response_json as an empty array.
                var response_json = ([]);
                    
                if (place_count > 0) {
                    // Pick fields needed by the autocomplete from the resulting JSON and add
                    // them to response_json array.
                    response_json = $.map(data, function (item) {
                        return {
                            label: item.name + ' (' + item.category + ')' + ' - ' + parseFloat(item.distance).toFixed(2) + "mi", 
                            value: item.name,
                            id: item.id
                        };
                    });
                }
                
                // Call the response function with the a copy of the response JSON.
                var temp = response_json.slice(0);
                temp.push({
                    label: 'Expanding search results with factual.com...', 
                    value: '',
                    id: ''
                });
                response(temp);
                    
                if (place_limit > 0) {
                    // If additional places are required, fetch places from Factual. Pick fields needed
                    // by the autocomplete from the resulting JSON and add them to response_json array.
                    var my_filters = {
                        "$search": request.term,
                        "$loc":{
                            "$within":{
                                "$center":[[myLatitude, myLongitude], 50000]
                            }
                        }
                    };

                    var options = {
                        api_key: 'SIKk9ulwxwodsqkZwpxfmbJr7EtuVHjwNyx2JO8pzGMCNBtsJPW3GcWZTJUhJ7ee',
                        limit: place_limit,
                        filters: JSON.stringify(my_filters)
                    };

                    $.ajax({
                        url: 'http://api.factual.com/v2/tables/s4OOB4/read',
                        data: options,
                        dataType: 'jsonp',
                        success : function(data) {
                            if (data.status != 'ok') {
                                alert('factual error');
                            } else {
                                data = data.response;
                                if (data.rows > 0) {
                                    data = data.data;
                                    $.map(data, function (item) {
                                        var category = item[12];
                                        if (category== null) {
                                            category = '';
                                        }
                                        
                                        // Vars necessary for the autocomplete entry
                                        var distance = get_distance_between(myLatitude, myLongitude, item[15], item[16]);
                                        var last_index = category.lastIndexOf('>');
                                        var category_name = category;
                                        if (last_index != -1) {
                                            category_name =  category.substr(last_index + 2);
                                        }
                                        
                                        response_json.push({
                                            label: '*' + item[2] + ' (' + category_name + ') - ' + distance.toFixed(2) + "mi", 
                                            value: item[2],
                                            id: 'factual',
                                            name: item[2],
                                            latitude: item[15],
                                            longitude: item[16],
                                            'category': category,
                                            factual_id: item[1]
                                        });
                                    }); 
                                }
                                response_json.push({
                                    label: "Create place (it's easy!)", 
                                    value: '', 
                                    id: 'new place'
                                });
                                
                                // Call the response function with the response JSON.
                                if (!item_selected) {
                                    response(response_json);
                                }
                            }
                        },
                        jsonp: 'jsoncallback'
                    });
                }
            });
        },
        // When an item is selected, update the location text as well as the hidden fields.
        select: function (event, ui) {
            item_selected = true;
            
            if (ui.item.id == 'new place') {
                show_add_location_modal();
            } else {
                $('#plan_location').val(ui.item.value);
                $('#plan_location_id').val(ui.item.id);
                $('#plan_location_name').val(ui.item.value);
            
                // Clear and set the additional hidden fields only if the selected place is from Factual.
                $('#new_place_name').val('');
                $('#new_place_category').val('');
                $('#new_place_latitude').val('');
                $('#new_place_longitude').val('');
                $('#new_place_factual_id').val('');
            
                if (ui.item.name != undefined) {
                    $('#new_place_name').val(ui.item.name);
                    $('#new_place_category').val(ui.item.category);
                    $('#new_place_latitude').val(ui.item.latitude);
                    $('#new_place_longitude').val(ui.item.longitude);
                    $('#new_place_factual_id').val(ui.item.factual_id);
                }
            
                populate_selectable_events();
            }
            
            toggle_time_day_buttons();
        }
    });
}