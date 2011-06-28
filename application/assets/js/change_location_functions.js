$(function() {
    initialize_change_location_panel();
    
    // Assign the click event(s).
    $('#change_location').click(function () {
        show_change_location_panel();
        return false;
    });
    
    $('#close_change_location').click(function () {
        hide_change_location_panel();
    });
});

// Keep track of all markers.
var change_location_marker_array = [];

// Initializes the change location panel.
function initialize_change_location_panel() {    
    // Set up the in-field labels.
    $('div.change_location_panel label').inFieldLabels();
    
    // Push the current location onto the marker list.
    change_location_marker_array.push(new google.maps.Marker({
        position: new google.maps.LatLng(myLatitude, myLongitude), 
        map: map,
        draggable: true,
        title:"Your location!"
    }));
    
    // Set up the autocomplete.
    $('#change_location_search').autocomplete({
        minLength: 2,
        source: function (request, response) {
            var places_request = {
                location: new google.maps.LatLng(myLatitude, myLongitude),
                radius: 10000,
                name: request.term,
                type: ['locality']
            };
            
            var places_service = new google.maps.places.PlacesService(map);
            places_service.search(places_request, function (results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    // Clear all markers and add the new ones.
                    clear_change_location_markers();
                                        
                    // Step through the results.
                    $.map(results, function (entry) {
                        // Create the marker.
                        var temp_marker = new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng(entry.geometry.location.lat(), entry.geometry.location.lng()),
                            title: entry.name
                        });
                        
                        // Assign the click event.
                        google.maps.event.addListener(temp_marker, 'click', function() {
                            alert('here');
                        });
                        
                        // Add the marker to the marker list.
                        change_location_marker_array.push(temp_marker);
                    });
                    
                    if (change_location_marker_array.length > 1) {
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
                    } else if (change_location_marker_array.length == 1) {
                        map.setCenter(change_location_marker_array[0].position);
                        map.setZoom(11);
                    }
                    
                }
            });
        },
        success: function (data) {
            console.log('success');
            console.log(data);
        }
    });
}

// Shows the panel.
function show_change_location_panel() {
    // Switch to the map tab.
    if ($("#map_data_tabs .ui-state-active a").attr('href') != '#map_tab') {
        $("#map_data_tabs").tabs('select', '#map_tab');
    }
    $('#map_tab').animate({
        height: (250 + $('div.change_location_panel').height()) + 'px'
    });
    
    $('div.change_location_panel').show('fast');
}

// Hides the panel.
function hide_change_location_panel() {
    $('div.change_location_panel').hide('fast');
        
    $('#map_tab').animate({
        height: '250px'
    });
}
    
// Remove all markers and update the map accordingly.
function clear_change_location_markers () {
    $.map(change_location_marker_array, function (entry) {
        entry.setMap(null);
    });
    
    change_location_marker_array.length = 0;
}

function get_min_marker(lat_lng) {
    var min = 360;
    
    if (lat_lng) {
        $.map(change_location_marker_array, function (item) {
            if (item.position.lat() < min) {
                min = item.position.lat();
            }
        });
    } else {
        $.map(change_location_marker_array, function (item) {
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
        $.map(change_location_marker_array, function (item) {
            if (item.position.lat() > max) {
                max = item.position.lat();
            }
        });
    } else {
        $.map(change_location_marker_array, function (item) {
            if (item.position.lng() > max) {
                max = item.position.lng();
            }
        });
    }
    
    return max;
}