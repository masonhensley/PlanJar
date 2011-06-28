var myLatitude;
var myLongitude;
var current_day_offset = 0;

// Run when the DOM is loaded.
$(function() {
    // places map
    location_data();
});

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
                
                // Update the user's profile with the new information.
                $.get('/home/update_user_location', {
                    latitude: myLatitude,
                    longitude: myLongitude,
                    auto: true
                }, function (data) {
                    if (data == 'prompt new location') {
                        // Open the change location dialog.
                        alert('Your new location estimate is more than 10 miles away from your last location. Please update your location.');

                        show_change_location_panel();
                        $('#change_location_content').dialog('open');
                    } else if (data != 'success') {
                        alert(data);
                    }
                });
                
                // Update the city name.
                update_current_city_name();
                
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
            
    var temp_marker = new google.maps.Marker({
        position: myLatlng, 
        map: map, 
        draggable: true,
        title:"Your location!"
    });
    
    // Assign the click event.
    google.maps.event.addListener(temp_marker, 'click', change_location_marker_click);
    
    // Add the marker to the marker list.
    map_marker_array.push(temp_marker);
}

// populates the popular location main panel
function load_visible_plans(){
    $.get('/home/load_popular_locations', {
        'selected_groups': get_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
        $('#visible_plans_panel').html(data); 
    });
}

function get_groups() {
    var return_list = ([]);
    $('div.group_selectable_wrapper li.group_selected').each(function (index, element) {
        return_list.push($(element).attr('group_id'));
    });
    return return_list;
}

// Gets the currently selected day offset (0-based)
function get_selected_day() {
    return $('#day_tabs ul.tabs li.day_selected a').attr('href');
}

// Return the city based off the user's coordinates.
function update_current_city_name() {
    var geocoder = new google.maps.Geocoder();
    var request = {
        location: new google.maps.LatLng(myLatitude, myLongitude)
    }
    
    geocoder.geocode(request, function (result, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            result = result[0].address_components;
            console.log(result[2].long_name + ', ' + result[4].short_name);
        }
    });
}

/* .....................Global map functions and vars..................... */
// Keep track of all markers.
var map_marker_array = [];

// Remove all markers and updates the map accordingly.
function clear_map_markers () {
    $.map(map_marker_array, function (entry) {
        entry.setMap(null);
    });
    
    map_marker_array.length = 0;
}