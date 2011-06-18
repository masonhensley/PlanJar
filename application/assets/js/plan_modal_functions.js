$(function() {
    initialize_plan_modal();
});

function initialize_plan_modal() {
    // Start the plan dialog box closed.
    $('#plan_content').dialog({
        autoOpen: false,
        width: 700,
        height: 300,
        resizable: false,
        show: 'clip',
        hide: 'explode'
    });
    
    // Initialize the make-a-plan modal.
    $('#make_a_plan').click(function() {
        
        $('#plan_content').dialog('open');
        
        // Initialize the in-field labels.
        $('#plan_content div.in-field_block label').inFieldLabels();
        
        // Initialize the plan location autocomplete instance.
        plan_location_autocomplete();
        
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
            }
        });
        
        // Make the time/day radios buttons.
        $('#plan_time').buttonset();
        $('#plan_day').buttonset();
        
        // Auto select the appropriate day.
        $('#plan_day ' + '[value=' + current_day_offset + ']').click();
        
        return false;
    });
}

function plan_location_autocomplete() {
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
                        "$and":[{
                            "$loc":{
                                "$within":{
                                    "$center":[[myLatitude, myLongitude],5000]
                                }
                            }
                        },

                        {
                            "$or":[{
                                "category":{
                                    "$bw":"Arts"
                                }
                            },

                            {
                                "category":{
                                    "$bw":"Food"
                                }
                            }]
                        }]
                    }
                        

                    var options = {
                        api_key: 'SIKk9ulwxwodsqkZwpxfmbJr7EtuVHjwNyx2JO8pzGMCNBtsJPW3GcWZTJUhJ7ee',
                        limit: place_limit
                    };

                    $.ajax({
                        url: 'http://api.factual.com/v2/tables/s4OOB4/read?filters=' + escape(JSON.stringify(my_filters)),
                        data: options,
                        dataType: 'jsonp',
                        success : function(data) {
                            console.log(JSON.parse(data));
                        }
                    });
                }
                
                response(response_json);
            });
        },
        // When an item is selected, update the location text as well as the hidden
        // id field.
        select: function (event, ui) {
            $('#plan_location').val(ui.item.value);
            $('#plan_location_id').val(ui.item.id);
        }
    });
}