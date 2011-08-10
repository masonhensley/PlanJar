$(function() {
    initialize_change_location_panel();
});

// Initializes the change location panel.
function initialize_change_location_panel() {
    // Assign the click event(s).
    $('#change_location').click(function () {
        if ($('.change_location_panel').css('display') == 'none') {
            // Switch to the map tab.
            show_data_container('#map_content', show_change_location_panel);
        }
        return false;
    });
    
    $('#close_change_location').click(function () {
        hide_change_location_panel();
    });
    
    // Set up the in-field labels.
    $('.change_location_panel label').inFieldLabels();
    
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
                
                // Push the current location onto the marker list.
                var temp_marker = new google.maps.Marker({
                    position: new google.maps.LatLng(myLatitude, myLongitude), 
                    map: map,
                    title:"Your location!",
                    icon: 'http://www.google.com/mapfiles/arrow.png',
                    draggable: true
                });
                map_marker_array.push(temp_marker);
    
                // Assign the click events
                google.maps.event.addListener(temp_marker, 'click', change_location_marker_click);
                google.maps.event.addListener(temp_marker, 'dragend', change_location_marker_click);
                                        
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
                    
                calculate_map_bounds();
            }
        });
    });
    
    // Use current location
    $('#use_cur_location').click(function() {
        get_current_location(function (latitude, longitude) {
            // Store the coordinates
            myLatitude = latitude;
            myLongitude = longitude;
            
            // Update the profile with the new location
            $.get('/home/update_user_location', {
                'latitude': latitude,
                'longitude': longitude,
                auto: false
            });
            
            // Hide the panel and update the map
            hide_change_location_panel();
            map_user_position();
            update_current_city_name();
        });
    });
}

// Shows the panel.
function show_change_location_panel() {
    // Extend the data box
    $('.data_container_wrapper').animate({
        height: ($('.change_location_panel').height() + 300) + 'px'
    });
    
    $('.change_location_panel').show('fast');
    
    // Auto-select the search box.
    $('#change_location_search').focus();
}

// Hides the panel.
function hide_change_location_panel() {
    // Blur out the search box
    $('#change_location_search').val('');
    $('#change_location_search').blur();
    
    // Hide the panel
    $('div.change_location_panel').hide('fast');
        
    // Reduce the data box
    $('.data_container_wrapper').animate({
        height: '300px'
    });
}

// Handles a change of location marker click
function change_location_marker_click(mouse_event) {
    // Update the user's coordinates.
    $.get('/home/update_user_location', {
        auto: false,
        latitude: mouse_event.latLng.lat(),
        longitude: mouse_event.latLng.lng()
    }, function () {
        hide_change_location_panel();
        myLatitude = mouse_event.latLng.lat();
        myLongitude = mouse_event.latLng.lng();
        map_user_position();
        update_current_city_name();
    });
}