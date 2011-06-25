$(function() {
    var change_location_object = new change_location();
    
    // Assign the click event(s).
    $('#change_location').click(function () {
        change_location_object.show_panel();
        return false;
    });
    
    $('#close_change_location').click(function () {
        change_location_object.hide_panel();
    });
});

// Change location object.
function change_location() {
    // Variables
    //
    var marker_array = ([]);
     
    // Constructor
    //
    
    // Set up the in-field labels.
    $('div.change_location_panel label').inFieldLabels();
    
    // Push the current location onto the marker list.
    marker_array.push(new google.maps.Marker({
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
                location: change_location_latlng,
                radius: 10000,
                name: request.term
            };
            
            var places_service = new google.maps.places.PlacesService(change_location_map);
            places_service.search(places_request, function (results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    console.log(results);
                    clear_markers(marker_array);
                    console.log('pre map');
                    $.map(results, function (entry) {
                        add_marker(entry, marker_array, change_location_map);
                    });
                }
            });
        },
        success: function (data) {
            console.log(data);
        }
    });
    
    // Methods
    //
    
    // Shows the panels.
    this.show_panel = function() {
        // Switch to the map tab.
        if ($("#map_data_tabs .ui-state-active a").attr('href') != '#map_tab') {
            $("#map_data_tabs").tabs('select', '#map_tab');
        }
        $('#map_tab').animate({
            height: (250 + $('div.change_location_panel').height()) + 'px'
        });
    
        $('div.change_location_panel').show('fast');
    }
    
    // Hides the panels.
    this.hide_panel = function () {
        $('div.change_location_panel').hide('fast');
        
        $('#map_tab').animate({
            height: '250px'
        });
    }
}

    
    
    


function clear_markers(marker_array) {
    $.map(marker_array, function (entry) {
        entry.setMap(null);
    });
    marker_array = ([]);
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