$(function() {
    initialize_change_location_panel();
});

// Initializes the change location panel.
function initialize_change_location_panel() {
    // Assign the click event(s).
    $('#change_location').click(function () {
        if ($('.change_location_panel').css('display') == 'none') {
            // Switch to the map tab.
            show_data_container('#map_data', show_change_location_panel);
        }
        return false;
    });
    
    $('#close_change_location').click(function () {
        hide_change_location_panel();
    });
    
    // Set up the in-field labels.
    $('.change_location_panel label').inFieldLabels();
    
    // Push the current location onto the marker list.
    var temp_marker = new google.maps.Marker({
        position: new google.maps.LatLng(myLatitude, myLongitude), 
        map: map,
        title:"Your location!"
    });
    
    map_marker_array.push(temp_marker);
    
    // Assign the click event.
    google.maps.event.addListener(temp_marker, 'click', change_location_marker_click);
    
    // Get data when the input is changed.
    $('#change_location_search').bind('keyup', function () {
        var places_request = {
            location: new google.maps.LatLng(myLatitude, myLongitude),
            radius: 5000,
            name: $(this).val()
        };
            
        var places_service = new google.maps.places.PlacesService(map);
        places_service.search(places_request, function (results, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                // Clear all markers and add the new ones.
                clear_map_markers();
                                        
                // Step through the results.
                $.map(results, function (entry) {
                    // Create the marker.
                    var temp_marker = new google.maps.Marker({
                        map: map,
                        position: new google.maps.LatLng(entry.geometry.location.lat(), entry.geometry.location.lng()),
                        title: entry.name
                    });
                        
                    // Assign the click event.
                    google.maps.event.addListener(temp_marker, 'click', change_location_marker_click);
                        
                    // Add the marker to the marker list.
                    map_marker_array.push(temp_marker);
                });
                    
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
                    map.setCenter(map_marker_array[0].position);
                    map.setZoom(10);
                }
                    
            }
        });
    });
}

// Shows the panel.
function show_change_location_panel() {
    $('.data_container_wrapper').animate({
        height: ($('.change_location_panel').height() + 300) + 'px'
    });
    
    $('.change_location_panel').show('fast');
    
    // Auto-select the search box.
    $('#change_location_search').select();
}

// Hides the panel.
function hide_change_location_panel() {
    $('div.change_location_panel').hide('fast');
        
    $('.data_container_wrapper').animate({
        height: '300px'
    });
    
    clear_map_markers();
}

function get_min_marker(lat_lng) {
    var min = 360;
    
    if (lat_lng) {
        $.map(map_marker_array, function (item) {
            if (item.position.lat() < min) {
                min = item.position.lat();
            }
        });
    } else {
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
        $.map(map_marker_array, function (item) {
            if (item.position.lat() > max) {
                max = item.position.lat();
            }
        });
    } else {
        $.map(map_marker_array, function (item) {
            if (item.position.lng() > max) {
                max = item.position.lng();
            }
        });
    }
    
    return max;
}

// Handles a change of location marker click
function change_location_marker_click(mouse_event) {
    // Update the user's coordinates.
    $.get('/home/update_user_location', {
        auto: false,
        latitude: mouse_event.latLng.lat(),
        longitude: mouse_event.latLng.lng()
    }, function (data) {
        if (data != 'success') {
            alert(data);
        } else {
            hide_change_location_panel();
            myLatitude = mouse_event.latLng.lat();
            myLongitude = mouse_event.latLng.lng();
            map_user_position();
            update_current_city_name();
        }
    });
}