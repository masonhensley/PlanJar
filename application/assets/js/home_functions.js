// Vars
var myLatitude;
var myLongitude;
var myCity;
var current_day_offset = 0;
var map_tab_opened = false; // Used to resize the map the first time it's shown (Google fuck up)
var map;

// Run when the DOM is loaded.
$(function() {
    initialize_map();
});

// Gets the user's current location
// Calls the callback function on success with parameters of latitude and longitude
function get_current_location(callback) {
    if (navigator.geolocation) 
    {
        // Location callback
        navigator.geolocation.getCurrentPosition(function (position) {  
            var latitude=position.coords.latitude;
            var longitude=position.coords.longitude;
            if (callback != undefined) {
                callback(latitude, longitude);
            }
        },  function () {
            // Error callback
            alert('Your position is unavailable at this time.');
        });
    }
}

// Initializes the map and updates the user's location with the server'
function initialize_map() {
    get_current_location(function (latitude, longitude) {
        // Update the user's profile with the new information.
        $.get('/home/update_user_location', {
            'latitude': latitude,
            'longitude': longitude,
            auto: true
        }, function (data) {
            data = $.parseJSON(data);
            if (data.status == 'adjusted') {
                // Location automatically adjusted
                alert(data.text);
                
                // Assign the latitude and longitude vars
                myLatitude = latitude;
                myLongitude = longitude;
                
                // Map the user's position and show the map
                map_user_position();
                show_data_container('#map_data');
            } else if (data.status == 'from_profile') {
                // Assign the longitude and latitude coordinates from the server to the js variables
                myLatitude = parseFloat(data.loc[0]);
                myLongitude = parseFloat(data.loc[1]);
                
                map_user_position();
            } else if (data.status == 'silent') {
                map_user_position();
            }
            
            // Update the city name.
            update_current_city_name();
        });
                
        // Create the map
        var map_options = {
            zoom: 14,
            center: new google.maps.LatLng(latitude,longitude),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        map = new google.maps.Map(document.getElementById("map"), map_options);
    });
}

// Returns a list of selected groups.
function get_selected_groups() {
    var return_list = ([]);
    if(!$('.network_tab').hasClass('network_active'))
    {
        $('.selectable_group.selected_group').each(function (index, element) {
            return_list.push($(element).attr('group_id'));
        });
    }else if($('.network_tab').hasClass('network_active'))
    {
        return_list.push($('.network_active').attr('group_id'));
    }
    // if nothing is selected, you can check the php variable for (false)
    return return_list;
}

// Gets the currently selected day offset (0-based)
function get_selected_day() {
    return $('.days_panel .day.day_selected').attr('day_offset');
}

function get_selected_location(){
    return $('.selected_location').attr('place_id');
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
            
            // Find the city
            myCity = '';
            var index = $.inArray('locality', result.types);
            if (index != -1) {
                myCity = result[index].long_name;
            }
            
            // Find the state
            index = $.inArray('administrative_level_1', result.types);
            if (index  != -1) {
                myCity += ', ' + result[index].short_name;
            }
            $('#using_location').html('Using location: ' + myCity);
        }
    });
}

/* .....................Global functions and vars..................... */
// Keep track of all markers.
var map_marker_array = [];

// Remove all markers and updates the map accordingly.
function clear_map_markers () {
    $.map(map_marker_array, function (entry) {
        entry.setMap(null);
    });
    
    map_marker_array.length = 0;
}

// Hides all data containers
function hide_data_containers() {
    
    $('.tab_bar .data_tab').removeClass('tab_selected');
    $('.data_container:visible').hide('slide', {}, 'fast', function () {
        $('.data_container_wrapper').hide('blind', {}, 'fast');
    });
}

// Shows the data container specified in the argument (takes care of closing beforehand, too)
function show_data_container(data_div, callback) {
    // Make callback optional.
    if (callback == undefined) {
        callback = function() {};
    }
    
    $('.change_location_panel').hide(); // closes the 'change location' div that gets added in the map div
    
    // If no tab is selected, show the wrapper.
    if (!$('.tab_bar .data_tab').hasClass('tab_selected')) {
        $('.data_container_wrapper').show('blind', {}, 'fast', function () {
            show_data_wrapper(data_div, callback);
        });
    } else {
        show_data_wrapper(data_div, callback);
    }
}

// Displays the data panel within the wrapper
function show_data_wrapper(data_div, callback) {
    // Select the appropriate tab.
    $('.tab_bar .data_tab').removeClass('tab_selected');
    $('.tab_bar [assoc_div="' + data_div + '"]').addClass('tab_selected');
        
    // Only show a container if it's not already visible.
    if ($(data_div).css('display') == 'none') {
        if ($('.data_container:visible').length > 0) {
            // Hide any visible data containers.
            $('.data_container:visible').hide('slide', {}, 'fast', function() {
                show_data_panel(data_div, callback);
            });
        } else {
            show_data_panel(data_div, callback);
        }
    } else {
        callback();
    }
}

// Shows the correct container and resizes the map.
function show_data_panel(data_div, callback) {
    // Show the appropriate container
    $(data_div).show('slide', {}, 'fast', function () {
        callback();
        
        // Resize the map after the animation finishes to eliminate the missing tile errors.
        if (!map_tab_opened) {
            google.maps.event.trigger(map, 'resize');
            calculate_map_bounds();
            map_tab_opened = true;
        }
    });
}

// Puts the user's position on the map and centers to it.
function map_user_position() {
    clear_map_markers();
    
    map_marker_array.push(new google.maps.Marker({
        position: new google.maps.LatLng(myLatitude, myLongitude),
        map: map,
        title: 'Your location',
        icon: 'http://www.google.com/mapfiles/arrow.png'
    }));
    
    map.setCenter(new google.maps.LatLng(myLatitude, myLongitude));
    map.setZoom(14);
}

// Calculates and sets the bounds for the map based on map_marker_array (global var)
function calculate_map_bounds() {
    if (map_marker_array.length > 1) {
        // Calculate the necessary viewport.
        var min_lat = get_min_marker(true);
        var min_lng = get_min_marker(false);
        var max_lat = get_max_marker(true);
        var max_lng = get_max_marker(false);
                    
        var bounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(min_lat, min_lng),
            new google.maps.LatLng(max_lat, max_lng)
            );
                                                    
        map.fitBounds(bounds);
    } else if (map_marker_array.length == 1) {
        // Center and zoom around the one location
        map.setCenter(map_marker_array[0].position);
        map.setZoom(14);
    }
}

// The following two functions get the minimum or the maximum latitude or longitude
// as specified in the parameter. Used in calculate_map_bounds.
function get_min_marker(lat_lng) {
    var min = 360;
    
    if (lat_lng) {
        // Latitude
        $.map(map_marker_array, function (item) {
            if (item.position.lat() < min) {
                min = item.position.lat();
            }
        });
    } else {
        // Longitude
        $.map(map_marker_array, function (item) {
            if (item.position.lng() < min) {
                min = item.position.lng();
            }
        });
    }
    
    return min;
}
function get_max_marker(lat_lng) {
    var max = -360;
    
    if (lat_lng) {
        // Latitude
        $.map(map_marker_array, function (item) {
            if (item.position.lat() > max) {
                max = item.position.lat();
            }
        });
    } else {
        // Longitude
        $.map(map_marker_array, function (item) {
            if (item.position.lng() > max) {
                max = item.position.lng();
            }
        });
    }
    
    return max;
}