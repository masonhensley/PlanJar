// Keep track of all markers.
var map_marker_array = [];

// Populates the map with the given data
// If the given data is empty, only the user's location is shown
function populate_map(data, closure_function) {
    // Only populate the map if it's been initialized'
    if (map != undefined) {
        clear_map_markers();
    
        // User's location'
        map_marker_array.push(new google.maps.Marker({
            position: new google.maps.LatLng(myLatitude, myLongitude),
            map: map,
            title: 'Your location',
            icon: '/application/assets/images/map_markers/arrow.png'
        }));
    
        for (var i = 0; i < data.length; ++i) {
            var temp_marker = new google.maps.Marker({
                position: new google.maps.LatLng(data[i][1], data[i][2]),
                map: map,
                title: data[i][0],
                icon: '/application/assets/images/map_markers/marker' + (i + 1) + '.png'
            });
        
            // Assign the click event
            google.maps.event.addListener(temp_marker, 'click', closure_function(i));
        
            map_marker_array.push(temp_marker);
        }
    
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

// Remove all markers and updates the map accordingly.
function clear_map_markers () {
    $.map(map_marker_array, function (entry) {
        entry.setMap(null);
    });
    
    map_marker_array.length = 0;
}

// Puts the user's position on the map and centers to it.
function map_user_position() {
    clear_map_markers();
    
    map_marker_array.push(new google.maps.Marker({
        position: new google.maps.LatLng(myLatitude, myLongitude),
        map: map,
        title: 'Your location',
        icon: '/application/assets/images/map_markers/arrow.png'
    }));
    
    calculate_map_bounds();
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