var myLatitude;
var myLongitude;
var myCity;
var myAddress;

// Run when the DOM is loaded.
$(function() {
    
    // places map
    location_data();
   
    $( "#tabs" ).tabs({
        collapsible: true
    });

    $( ".tabs-bottom .ui-tabs-nav, .tabs-bottom .ui-tabs-nav > *" )
    .removeClass( "ui-corner-all ui-corner-top" )
    .addClass( "ui-corner-bottom" );
    
    // Set up the day of the week tabs.
    $("ul.tabs li:first").addClass("day_selected").show(); //Activate first tab

    //On Click Event
    $("ul.tabs li").click(function() {

        $("ul.tabs li.day_selected").removeClass("day_selected"); //Remove any "day_selected" class
        $(this).addClass("day_selected"); //Add "day_selected" class to selected tab
        
        // Call the callback function.
        on_day_change($('ul.tabs li.day_selected a').attr('href'));

        return false;
    });
    
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
        $('#plan_location').autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.get('/home/find_places', {
                    // Get places from the PlanJar server.
                    needle: request.term,
                    latitude: myLatitude,
                    longitude: myLongitude
                }, function (data) {
                    // Parse the JSON text.
                    data = $.parseJSON(data);
                    
                    var place_count = data.count;
                    var place_limit = 10 - data.count;
                    
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
                                console.log($.parseJSON(data));
                            }
                        });
                    }
                });
            },
            // When an item is selected, update the location text as well as the hidden
            // id field.
            select: function (event, ui) {
                $('#plan_location').val(ui.item.value);
                $('#plan_location_id').val(ui.item.id);
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
            }
        });
        
        return false;
    });
    
   
    
// End of ready function.
});

function on_day_change(index) {
    console.log(index);
}


// This function takes the user's latitude and longitude and passes them
// to the yahoo reverse geocoding api and returns JSON encoded string of
// relevent info.  This is saved in global variables at top.'
function reverse_geocode_user()
{
    $.ajax({
        url: 'http://where.yahooapis.com/geocode',
        data: {
            location: myLatitude+'+'+myLongitude,
            appid: '5CXRiH44',
            flags: 'J'
        },
        dataType: 'jsonp',
        success: function(data) {
            alert(data);
        }
    });
}

// this function is called on an onClick event for a day or group; 
// it updates the dashboard information based on the day and groups
// selected
function update()
{
    
   
}

var initialLocation;
var browserSupportFlag;
var map;

function location_data() {
    
    if (navigator.geolocation) 
    {
        navigator.geolocation.getCurrentPosition
        ( 
            function (position) 
            {  
                myLatitude=position.coords.latitude;
                myLongitude=position.coords.longitude;
                
                // sets variables myCity, myAddress
                reverse_geocode_user();
                
                mapThisGoogle(position.coords.latitude, position.coords.longitude);
            }, 
            // next function is the error callback
            function (error)
            {
                switch(error) {
                    case error.TIMEOUT:
                        alert ('Timeout');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert ('Position unavailable');
                        break;
                    case error.PERMISSION_DENIED:
                        alert ('Permission denied');
                        break;
                    case error.UNKNOWN_ERROR:
                        alert ('Unknown error');
                        break;
                }
            });
    }
}

function mapServiceProvider(latitude,longitude)
{
    // querystring function from prettycode.org: 
    // http://prettycode.org/2009/04/21/javascript-query-string/
 
    if (window.location.querystring['serviceProvider']=='Yahoo')
    {
        mapThisYahoo(latitude,longitude);
    }
    else
    {
        mapThisGoogle(latitude,longitude);
    }
}

// places the google map
function mapThisGoogle(latitude,longitude)
{
    var myLatlng = new google.maps.LatLng(latitude,longitude);
        
    var myOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
        
    map = new google.maps.Map(document.getElementById("map"), myOptions);
            
    your_location_marker = new google.maps.Marker({
        position: myLatlng, 
        map: map, 
        draggable: true,
        title:"Your location!"
    });
}
