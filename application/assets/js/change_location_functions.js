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

// Initializes the change location panel.
function initialize_change_location_panel() {
    // Keep track of all markers.
    var change_location_marker_array = ([]);
    
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
                name: request.term
            };
            
            var places_service = new google.maps.places.PlacesService(map);
            places_service.search(places_request, function (results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    console.log(results);
                    clear_change_location_markers();
                    console.log('pre map');
                    $.map(results, function (entry) {
                        change_location_marker_array.push(new google.maps.Marker({
                            map: map,
                            position: new google.maps.LatLng({
                                lat: entry.geometry.location.Ha,
                                lng: entry.geometry.location.Ia
                            }),
                            title: entry.name
                        }));
                        console.log(change_location_marker_array);
                    });
                }
            });
        },
        success: function (data) {
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
    change_location_marker_array = ([]);
}

function add_marker(data, marker_array, map) {
    var new_marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng({
            lat: data.geometry.location.Ha,
            lng: data.geometry.location.Ia
        }),
        title: data.name
    })
    marker_array.push(new_marker);
    console.log(marker_array);
}