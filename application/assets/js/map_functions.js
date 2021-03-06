// Keep track of all markers.
var map_marker_array = [];

// Populates the map with the given data
// If the given data is empty, only the user's location is shown
function populate_map(data, closure_function, non_numbered) {
    // Only populate the map if it's been initialized'
    if (map != undefined) {
        clear_map_markers();
    
        $.map(data, function(item, index) {
            var icon;
            if (non_numbered != undefined) {
                icon = '/application/assets/images/map_markers/symbol_middot.png'
            } else {
                icon = '/application/assets/images/map_markers/number_' + (index + 1) + '.png';
            }
            
            console.log('map');
            var temp_marker = new google.maps.Marker({
                position: new google.maps.LatLng(item[1], item[2]),
                map: map,
                title: item[0],
                'icon': icon
            });
        
            // Assign the click event
            google.maps.event.addListener(temp_marker, 'click', closure_function(index));
        
            map_marker_array.push(temp_marker);
        });
        
        // User's location'
        map_marker_array.push(new google.maps.Marker({
            position: new google.maps.LatLng(myLatitude, myLongitude),
            map: map,
            title: 'Your location',
            icon: '/application/assets/images/map_markers/you.png'
        }));
    
        calculate_map_bounds();
    }
}

// Used to set up the click event for markers created for top locations
function location_marker_closure(index) {
    return function() {
        // Select the corresponding location and display info
        $('.location_tab').eq(index).click();
        show_data_container('#info_content');
    }
}

// Used to set up the click event for markers created for plans
function plan_marker_closure(plan_id) {
    return function() {
        // Click the necessary plan (the not clause prevents unselecting a plan)
        $('.plan_content, .friend_plan_content').not('.selected_plan, .selected_friend_plan').filter('[plan_id="' + plan_id + '"]').click();
        show_data_container('#info_content', function() {
            $('.view_plan_location').click();
        });
    }
}

// Used to set up the click event for the marker created for a selected location
function selected_location_marker_closure(index) {
    return function() {
        show_data_container('#info_content');
    }
}

// Remove all markers and updates the map accordingly.
function clear_map_markers () {
    $.map(map_marker_array, function (entry) {
        entry.setMap(null);
    });
    
    map_marker_array.length = 0;
}

// Puts the user's position on the map and centers to it.
function map_user_position() {
    populate_map(([]));
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
    }
    else {
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