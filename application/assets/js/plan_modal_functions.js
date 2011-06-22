$(function() {
    initialize_plan_modal();
});

// Sets up the modal.
function initialize_plan_modal() {
    // Start the plan dialog box closed.
    $('#plan_content').dialog({
        autoOpen: false,
        width: 600,
        height: 250,
        resizable: false,
        show: 'clip',
        hide: 'explode'
    });
    
    // Initialize the in-field labels.
    $('#plan_content div.in-field_block label').inFieldLabels();
        
    // Initialize the plan location autocomplete instance.
    $('#plan_location').autocomplete({
        minLength: 2,
        source: function (request, response) {
            // Get places from the PlanJar server.
            $.get('/home/find_places', {
                needle: request.term,
                latitude: myLatitude,
                longitude: myLongitude
            }, function (data) {
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
                                "$center":[[myLatitude, myLongitude],5000]
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
                            console.log(data);
                            if (data.status != 'ok') {
                                alert('factual error');
                            } else {
                                data = data.response;
                                if (data.rows > 0) {
                                    data = data.data;
                                    $.map(data, function (item) {
                                        var category = item[12];
                                        var category_name = '';
                                        if (category != null) {
                                            var last_gt = item[12].lastIndexOf('>');
                                            if (last_gt != -1) {
                                                category = category.substr(last_gt + 1);
                                            }
                                            category = $.trim(category);
                                            category_name = ' (' + category + ')';
                                        } else {
                                            category = ''
                                        }
                                        
                                        var distance = get_distance_between(myLatitude, myLongitude, item[15], item[16]);
                                        
                                        response_json.push({
                                            label: '*' + item[2] + category_name + ' - ' + distance.toFixed(2) + "mi", 
                                            value: item[2],
                                            id: 'factual',
                                            name: item[2],
                                            latitude: item[15],
                                            longitude: item[16],
                                            'category': category
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
                                response(response_json);
                            }
                        },
                        jsonp: 'jsoncallback'
                    });
                }
            });
        },
        // When an item is selected, update the location text as well as the hidden fields.
        select: function (event, ui) {
            $('#plan_location').val(ui.item.value);
            $('#plan_location_id').val(ui.item.id);
            $('#plan_location_name').val(ui.item.value);
            
            // Set the additional hidden fields only if the selected place is from Factual.
            $('#new_place_name').val('');
            $('#new_place_category').val('');
            $('#new_place_latitude').val('');
            $('#new_place_longitude').val('');
            
            if (ui.item.name != undefined) {
                $('#new_place_name').val(ui.item.name);
                $('#new_place_category').val(ui.item.category);
                $('#new_place_latitude').val(ui.item.latitude);
                $('#new_place_longitude').val(ui.item.longitude);
            }
        }
    });
        
    // Initialize the plan category autocomplete instance.
    $('#plan_category').autocomplete({
        // Get info from the server.
        source: function (request, response) {
            $.get('/home/find_plan_categories', {
                needle: request.term
            }, function (data) {
                // Convert each item in the JSON from the server to the required JSON
                // form for the autocomplete and pass the result through the response
                // handler.
                data = $.parseJSON(data);
                if (data.count > 0) {
                    data = data.data;
                    response($.map(data, function (item) {
                        return {
                            label: item.category,
                            value: item.category,
                            id: item.id
                        };
                    }));
                } else {
                    response({
                        label: "No results found for '" + request.term + "'",
                        value: '',
                        id: ''
                    });
                }
            });
        },
        // When an item is selected, update the location text as well as the hidden
        // id field.
        select: function (event, ui) {
            $('#plan_category').val(ui.item.value);
            $('#plan_category_id').val(ui.item.id);
            $('#plan_category_name').val(ui.item.value);
        }
    });
        
    // Make the time/day radios buttons.
    $('#plan_time').buttonset();
    $('#plan_day').buttonset();
    
    // Auto-select the time of day.
    var date = new Date();
    var hours = date.getHours();
    if (hours < 5) {
        $('#plan_late_night').click();
    } else if (hours < 11) {
        $('#plan_morning').click();
    } else if (hours < 18) {
        $('#plan_afternoon').click();
    } else {
        $('#plan_night').click();
    }
    
    // Initialize the Validator plugin for the plan location.
    $('#make_plan').validate({
        rules: {
            plan_location_id: 'required',
            plan_category_id: 'required',
            plan_time_group: 'required',
            plan_day_group: 'required'
        },
        submitHandler: function (form) {
            $.get('/home/submit_plan', $(form).serialize(), function (data) {
                if (data == 'success') {
                    $('#plan_content').dialog('close');
                } else {
                    alert(data);
                }
            });
        },
        errorPlacement: function (error, element) {
            return true;
        }
    });
    
    // Initialize the make-a-plan modal.
    $('#make_a_plan').click(function() {
        // Update the selected day.
        var selected_day = $('#day_tabs ul.tabs li.day_selected a').attr('href');
        $('#plan_day [value=' + selected_day + ']').click();
        
        $('#plan_content').dialog('open');
    });
}

// Only allows input chosen from an autocomplete.
// All three arguments are DOM element names (as strings).
function lock_to_autocomplete(textbox_name, id_name, name_name) {
    // Get the id stored in the hidden field.
    var id = $(id_name).val();
    
    if (id == '') {
        // If id is empty, clear the location box.
        $(textbox_name).val('');
        
    } else {
        // A location was previously selected, so repopulate the location box with that
        // name (saved locally) This should make it clear to the user that
        // only a chosen item can be submitted.
        $(textbox_name).val($(name_name).val());
    }
    
    // Force the plan location and category fields to be chosen from the autocomplete.
    $('#plan_location').blur(function() {
        lock_to_autocomplete('#plan_location', '#plan_location_id', '#plan_location_name');
    });
    $('#plan_category').blur(function() {
        lock_to_autocomplete('#plan_category', '#plan_category_id', '#plan_category_name');
    });
    
    // Select today.
    $('#plan_day :first').click();
}

function get_distance_between(lat0, long0, lat1, long1) {
    return ((Math.acos(Math.sin(lat0 * Math.PI / 180) * Math.sin(lat1 * Math.PI / 180) 
        + Math.cos(lat0 * Math.PI / 180) * Math.cos(lat1 * Math.PI / 180) * Math.cos((long0 - long1)
            * Math.PI / 180)) * 180 / Math.PI) * 60 * 1.1515);
}