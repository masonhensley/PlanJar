$(function() {
    initialize_plan_modal();
});

function initialize_plan_modal() {
    // Opening click handler
    $('#create_plan').click(function () {
        // Add the city name to the handle text.
        if (myCity != undefined) {
            $('#create_plan_content .draggable_title_bar .text').html('Start a plan in ' + myCity);
        } else {
            $('#create_plan_content .draggable_title_bar .text').html('Start a plan');
        }

        $('#create_plan_content').show('fast');
    });
    
    // Closing click handler
    $('#cancel_plan').click(function () {
        $('#create_plan_content').hide();
    });
    
    // Make it draggable (with a handle).
    $('#create_plan_content').draggable({
        handle: '.draggable_title_bar'
    });
    
    // Left scroll
    $('#plan_left').click(function () {
        prev_plan_panel();
    });
    
    // Right scroll
    // This function essentially acts as a step-by-step validator.
    $('#plan_right').click(function() {
        // Check the current page before continuing on
        var current_index = parseInt($('.plan_page_content:visible').attr('page_index'));
        switch(current_index) {
            // First page
            case 0:
                // An autocomplete entry must have been chosen (this field is populated by the autocomplete)
                if ($('#plan_location_id').val() != '') {
                    next_plan_panel();
                }
                break;
                
            // Second page
            case 1:
                // Both time and day must be selected
                if ($('#plan_day .divset_selected, #plan_time .divset_selected').length == 2) {
                    initialize_event_select_page();
                    next_plan_panel();
                }
                break;
                
            // Third page
            case 2:
                // Make sure an event is selected or an event has been created
                if ($('#plan_event_select').val() != null || $('#event_title').val() != '') {
                    next_plan_panel();
                }
                break;
        }
    });
    
    // In-field label
    $('#create_plan_content .in-field_block label').inFieldLabels();
    
    // Initialize the plan location autocomplete instance.
    initialize_plan_autocomplete();
    
    // Divsets
    $('#plan_time, #plan_day, #plan_privacy_wrapper').divSet();
    
    // Initial select
    $('#plan_privacy_wrapper div').first().click();
    
    // Try to advance the plan panel when a time or a day is selected
    $('#plan_day, #plan_time').click(function () {
        $('#plan_right').click();
    });
    
    // TokenInput
    $('#invite_plan_users').tokenInput('/home/get_followers_invite', {
        hintText: 'Search followers...',
        preventDuplicates: true,
        queryParam: 'needle'
    });
    
    $('#invite_plan_groups').tokenInput('/home/get_groups_invite', {
        hintText: 'Search joined groups...',
        preventDuplicates: true,
        queryParam: 'needle'
    });
    
    // Submit
    $('#submit_plan').click(function () {
        $.get('/home/submit_plan?' + $('#plan_form').serialize(), {
            'plan_time': $('#plan_time .divset_selected').attr('plan_time'),
            'plan_day': $('#plan_day .divset_selected').attr('plan_day'),
            'privacy': $('#plan_privacy_wrapper .divset_selected').attr('priv_val')
        } ,function (data) {
            if (data == 'success') {
                $('#create_plan_content').hide();
                
                // Refresh the plan list.
                populate_plan_panel();
            } else {
                console.log(data);
            }
        });
    });
    
// End of DOM ready function
}

function initialize_event_select_page() {
    // Populate the header for the next page
    var time = $('#plan_time .divset_selected').html().toLowerCase();
    $('#plan_events_title').html("Here's what's happening at " + $('#plan_location_name').val() +
        ' on ' + $('#plan_day .divset_selected').html() + ' ' + time);
                    
    // Populate the event select
    $.get('/home/get_events_for_plan', {
        day: $('#plan_day .divset_selected').attr('plan_day'),
        time: $('#plan_time .divset_selected').attr('plan_time'),
        place_id: $('#plan_location_id').val()
    }, function (data) {
        $('#plan_event_select_wrapper').html(data);
                        
        // Handle the select change event (overwriting default functionality)
        $('#plan_event_select option').mousedown(function () {
            $(this).siblings().removeAttr('selected')
            $(this).attr('selected', 'selected');
            
            // Store the plan id
            $('#plan_event_id').val($(this).parent().val());
            
            // Reset and hide the title and privacy settings
            $('#event_title').val('');
            $('#event_title').blur();
            $('#plan_privacy_wrapper div').first().click();
            $('#event_title_wrapper').hide();
            
            // Hide both invite boxes for a closed event and just the group invite box for a loose event
            if ($(this).attr('priv_type') == 'strict') {
                $('#invite_plan_users_wrapper, #invite_plan_groups_wrapper').css('display', 'none');
            } else if ($(this).attr('priv_type') == 'open') {
                $('#invite_plan_users_wrapper').css('display', '');
                $('#invite_plan_groups_wrapper').css('display', 'none');
            }
            
            next_plan_panel();
        });
        
        // No title click handler
        $('#no_event_title').click(function () {
            // Clear the select
            $('#plan_event_select option[selected="selected"]').removeAttr('selected');
            
            // Reset and hide the title and privacy settings
            $('#plan_event_id').val('');
            $('#event_title').val('');
            $('#event_title').blur();
            $('#plan_privacy_wrapper div').first().click();
            $('#event_title_wrapper').hide();
            
            // Show both invite boxes
            $('#invite_plan_users_wrapper, #invite_plan_groups_wrapper').css('display', '');
            
            next_plan_panel();
        });
        
        // New event click handler
        $('#create_event').click(function () {
            // Clear the select
            $('#plan_event_select option[selected="selected"]').removeAttr('selected');
            
            // Reset and show the title and privacy settings
            $('#plan_event_id').val('');
            $('#event_title').blur();
            $('#event_title').focus();
            $('#plan_privacy_wrapper div').first().click();
            $('#event_title_wrapper').show();
            
            // Show both invite boxes
            $('#invite_plan_users_wrapper, #invite_plan_groups_wrapper').css('display', '');
        });
    });
}

// Scrolls to the previous plan panel
function prev_plan_panel() {
    var current_index = parseInt($('.plan_page_content:visible').attr('page_index'));
    
    if (current_index > 0) {
        $('.plan_page_content:visible').hide('slide', {
            direction: 'right'
        }, 'fast', function () {
            $('.plan_page_content[page_index="' + (current_index - 1) + '"]').show('slide', {
                }, 'fast');
        });
    }
}

// Scrolls to the next plan panel
function next_plan_panel() {
    var current_index = parseInt($('.plan_page_content:visible').attr('page_index'));
    
    if (current_index < 3) {
        $('.plan_page_content:visible').hide('slide', {
            }, 'fast', function () {
                $('.plan_page_content[page_index="' + (current_index + 1) + '"]').show('slide', {
                    direction: 'right'
                }, 'fast');
            });
    }
}

function get_distance_between(lat0, long0, lat1, long1) {
    return ((Math.acos(Math.sin(lat0 * Math.PI / 180) * Math.sin(lat1 * Math.PI / 180) 
        + Math.cos(lat0 * Math.PI / 180) * Math.cos(lat1 * Math.PI / 180) * Math.cos((long0 - long1)
            * Math.PI / 180)) * 180 / Math.PI) * 60 * 1.1515);
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
                // Keep track of whether an item was selecetd or not (delayed autocomplete items fix).
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
                    label: 'Expanding search results...', 
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
                                            category_name =  category.substr(last_index + 1);
                                        }
                                        
                                        response_json.push({
                                            label: '*' + item[2] + category_name + ' - ' + distance.toFixed(2) + "mi", 
                                            value: item[2],
                                            id: 'factual',
                                            name: item[2],
                                            latitude: item[15],
                                            longitude: item[16],
                                            'category': category,
                                            factual_id: item[1]
                                        });
                                    }); 
                                } else {
                                    response_json.push({
                                        label: "You've stumped us. Create a new place.", 
                                        value: '', 
                                        id: ''
                                    });
                                }
                                
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
            
            next_plan_panel();
        }
    });
}