$(function() {
    initialize_plan_modal();
});

function initialize_plan_modal() {
    // Opening click handler
    $('#create_plan').click(function () {
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
    $('#plan_right').click(function() {
        next_plan_panel();
    });
}

// Scrolls to the previous plan panel
function prev_plan_panel() {
    var current_index = parseInt($('.plan_page_content:visible').attr('page_index'));
    
    if (current_index > 0) {
        $('.plan_page_content:visible').hide('fast', 'slide', function () {
            $('.plan_page_content[page_index="' + (current_index - 1) + '"]').show('fast');
        });
    }
}

// Scrolls to the next plan panel
function next_plan_panel() {
    var current_index = parseInt($('.plan_page_content:visible').attr('page_index'));
    
    if (current_index < 3) {
        $('.plan_page_content:visible').hide('fast', 'slide', function () {
            $('.plan_page_content[page_index="' + (current_index + 1) + '"]').show('fast');
        });
    }
}


//// Sets up the modal.
//function initialize_plan_modal() {
//    // Click event
//    $('#create_plan').click(function () {
//        // Autoselects the day
//        $('#plan_day [plan_day=' + get_selected_day() + ']').click();
//        
//        // Autoselects the time of day
//        var date = new Date();
//        var hours = date.getHours();
//        if (hours < 5) {
//            $('#plan_time [plan_time="late_night"]').click();
//        } else if (hours < 11) {
//            $('#plan_time [plan_time="morning"]').click();
//        } else if (hours < 18) {
//            $('#plan_time [plan_time="afternoon"]').click();
//        } else {
//            $('#plan_time [plan_time="night"]').click();
//        }
//        
//        $('#create_plan_content input[type="text"], #create_plan_content input[type="hidden"]').val('');
//        $('#plan_location, #plan_title').blur();
//        
//        $('#create_plan_content').show('fast');
//        $('#plan_location').focus();
//    });
//    
//    $('#cancel_plan').click(function () {
//        $('#create_plan_content').hide();
//    });
//    
//    // Add the city name to the handle text.
//    $('#create_plan_content .draggable_title_bar').html('Start a plan');
//    if (myCity != undefined) {
//        $('#create_plan_content .draggable_title_bar').html('Start a plan in ' + myCity);
//    }
//    
//    // Make it draggable (with a handle).
//    $('#create_plan_content').draggable({
//        handle: '.draggable_title_bar'
//    });
//    
//    // Divset
//    $('#plan_time').divSet();
//    $('#plan_day').divSet();
//
//    // Initialize the in-field labels.
//    $('#create_plan_content .in-field_block label').inFieldLabels();
//        
//    // Initialize the plan location autocomplete instance.
//    var item_selected;
//    $('#plan_location').autocomplete({
//        minLength: 2,
//        source: function (request, response) {
//            // Get places from the PlanJar server.
//            $.get('/home/find_places', {
//                needle: request.term,
//                latitude: myLatitude,
//                longitude: myLongitude
//            }, function (data) {
//                // Keep track of whether an item was selecetd or not (delayed autocomplete items fix).
//                item_selected = false;
//                
//                // Parse the JSON text.
//                data = $.parseJSON(data);
//                    
//                var place_count = data.count;
//                var place_limit = 10 - place_count;
//                    
//                // We're done with count, so overwrite data with data.data (Peter Griffin laugh).
//                data = data.data;
//                    
//                // Set response_json as an empty array.
//                var response_json = ([]);
//                    
//                if (place_count > 0) {
//                    // Pick fields needed by the autocomplete from the resulting JSON and add
//                    // them to response_json array.
//                    response_json = $.map(data, function (item) {
//                        return {
//                            label: item.name + ' (' + item.category + ')' + ' - ' + parseFloat(item.distance).toFixed(2) + "mi", 
//                            value: item.name,
//                            id: item.id
//                        };
//                    });
//                }
//                
//                // Call the response function with the a copy of the response JSON.
//                var temp = response_json.slice(0);
//                temp.push({
//                    label: 'Expanding search results...', 
//                    value: '',
//                    id: ''
//                });
//                response(temp);
//                    
//                if (place_limit > 0) {
//                    // If additional places are required, fetch places from Factual. Pick fields needed
//                    // by the autocomplete from the resulting JSON and add them to response_json array.
//                    var my_filters = {
//                        "$search": request.term,
//                        "$loc":{
//                            "$within":{
//                                "$center":[[myLatitude, myLongitude], 50000]
//                            }
//                        }
//                    };
//
//
//                    var options = {
//                        api_key: 'SIKk9ulwxwodsqkZwpxfmbJr7EtuVHjwNyx2JO8pzGMCNBtsJPW3GcWZTJUhJ7ee',
//                        limit: place_limit,
//                        filters: JSON.stringify(my_filters)
//                    };
//
//                    $.ajax({
//                        url: 'http://api.factual.com/v2/tables/s4OOB4/read',
//                        data: options,
//                        dataType: 'jsonp',
//                        success : function(data) {
//                            if (data.status != 'ok') {
//                                alert('factual error');
//                            } else {
//                                data = data.response;
//                                if (data.rows > 0) {
//                                    data = data.data;
//                                    $.map(data, function (item) {
//                                        var category = item[12];
//                                        if (category== null) {
//                                            category = '';
//                                        }
//                                        
//                                        // Vars necessary for the autocomplete entry
//                                        var distance = get_distance_between(myLatitude, myLongitude, item[15], item[16]);
//                                        var last_index = category.lastIndexOf('>');
//                                        var category_name = category;
//                                        if (last_index != -1) {
//                                            category_name =  category.substr(last_index + 1);
//                                        }
//                                        
//                                        response_json.push({
//                                            label: '*' + item[2] + category_name + ' - ' + distance.toFixed(2) + "mi", 
//                                            value: item[2],
//                                            id: 'factual',
//                                            name: item[2],
//                                            latitude: item[15],
//                                            longitude: item[16],
//                                            'category': category,
//                                            factual_id: item[1]
//                                        });
//                                    }); 
//                                } else {
//                                    response_json.push({
//                                        label: "You've stumped us. Create a new place.", 
//                                        value: '', 
//                                        id: ''
//                                    });
//                                }
//                                
//                                // Call the response function with the response JSON.
//                                if (!item_selected) {
//                                    response(response_json);
//                                }
//                            }
//                        },
//                        jsonp: 'jsoncallback'
//                    });
//                }
//            });
//        },
//        // When an item is selected, update the location text as well as the hidden fields.
//        select: function (event, ui) {
//            item_selected = true;
//            
//            $('#plan_location').val(ui.item.value);
//            $('#plan_location_id').val(ui.item.id);
//            $('#plan_location_name').val(ui.item.value);
//            
//            // Clear and set the additional hidden fields only if the selected place is from Factual.
//            $('#new_place_name').val('');
//            $('#new_place_category').val('');
//            $('#new_place_latitude').val('');
//            $('#new_place_longitude').val('');
//            $('#new_place_factual_id').val('');
//            
//            if (ui.item.name != undefined) {
//                $('#new_place_name').val(ui.item.name);
//                $('#new_place_category').val(ui.item.category);
//                $('#new_place_latitude').val(ui.item.latitude);
//                $('#new_place_longitude').val(ui.item.longitude);
//                $('#new_place_factual_id').val(ui.item.factual_id);
//            }
//        }
//    });
//    
//    // Initialize the Validator plugin for the plan location.
//    $('#start_plan').validate({
//        rules: {
//            plan_location_id: 'required',
//            plan_time_group: 'required',
//            plan_day_group: 'required'
//        },
//        submitHandler: function (form) {
//            var data_string = $(form).serialize();
//            var other_data = {
//                'plan_time': $('#plan_time .divset_selected').attr('plan_time'),
//                'plan_day': $('#plan_day .divset_selected').attr('plan_day'),
//                'privacy': $('#privacy_wrapper .divset_selected').attr('priv_val')
//            }
//        
//            $.get('/home/submit_plan?' + data_string, other_data, function (data) {
//                if (data == 'success') {
//                    $('#create_plan_content').hide();
//                    // Refresh th eplan list.
//                    populate_plan_panel();
//                } else {
//                    alert(data);
//                }
//            });
//        },
//        errorPlacement: function (error, element) {
//            // Don't show errors.
//            return true;
//        }
//    });
//    
//    // Force the plan location and category fields to be chosen from the autocomplete.
//    $('#plan_location').blur(function() {
//        // Get the id stored in the hidden field.
//        var id = $('#plan_location_id').val();
//    
//        if (id == '') {
//            // If id is empty, clear the location box.
//            $('#plan_location').val('');
//        
//        } else {
//            // A location was previously selected, so repopulate the location box with that
//            // name (saved locally) This should make it clear to the user that
//            // only a chosen item can be submitted.
//            $('#plan_location').val($('#plan_location_name').val());
//        }
//    });
//    
//    // Invite click handlers
//    $('#invite_to_plan').click(function () {
//        $('#invite_plan_content').show('fast');
//    });
//    $('#close_invite_plan_content').click(function () {
//        $('#invite_plan_content').hide('fast');
//    });
//    
//    // TokenInput
//    $('#invite_plan_user').tokenInput('/home/get_followers_invite', {
//        hintText: 'Search followers...',
//        preventDuplicates: true,
//        queryParam: 'needle'
//    });
//    
//    $('#invite_plan_group').tokenInput('/home/get_groups_invite', {
//        hintText: 'Search joined groups...',
//        preventDuplicates: true,
//        queryParam: 'needle'
//    });
//    
//    // divset
//    $('#privacy_wrapper').divSet();
//    
//    // Initial privacy select
//    $('#privacy_wrapper :first').click();
//    
//}

function get_distance_between(lat0, long0, lat1, long1) {
    return ((Math.acos(Math.sin(lat0 * Math.PI / 180) * Math.sin(lat1 * Math.PI / 180) 
        + Math.cos(lat0 * Math.PI / 180) * Math.cos(lat1 * Math.PI / 180) * Math.cos((long0 - long1)
            * Math.PI / 180)) * 180 / Math.PI) * 60 * 1.1515);
}