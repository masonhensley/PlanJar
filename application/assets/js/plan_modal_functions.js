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
                    
                if (place_limit > 0) {
                    // If additional places are required, fetch places from Factual. Pick fields needed
                    // by the autocomplete from the resulting JSON and add them to response_json array.
                    var my_filters = {
                        //"$search": request.term,
                        "$search": "sushi",
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
                            console.log(JSON.parse(data));
                        }
                    });
                }
                
                // Call the response function with the response JSON.
                response(response_json);
            });
        },
        // When an item is selected, update the location text as well as the hidden fields.
        select: function (event, ui) {
            $('#plan_location').val(ui.item.value);
            $('#plan_location_id').val(ui.item.id);
            $('#plan_location_name').val(ui.item.value);
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
                response($.map(data, function (item) {
                    return {
                        label: item.category,
                        value: item.category,
                        id: item.id
                    };
                }));
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
        $('#plan_late_night').select();
    } else if (hours < 11) {
        $('#plan_morning').select();
    } else if (hours < 18) {
        $('#plan_afternoon').select();
    } else {
        $('#plan_night').select();
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