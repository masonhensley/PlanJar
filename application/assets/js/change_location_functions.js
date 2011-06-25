$(function() {
});

// Perform all change of location modal initialization
function initialize_change_location_modal() {
    $('#change_location_content').draggable({
        handle: 'div.modal_title'
    });
    
    // Set up the in-field labels.
    $('#change_location_content label').inFieldLabels();
    
    // Initialize a marker array.
    var marker_array = ([]);
    
    // Set up the map.
    var change_location_latlng = new google.maps.LatLng(myLatitude, myLongitude);
    var change_location_options = {
        zoom: 13,
        center: change_location_latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
        
    var change_location_map = new google.maps.Map(document.getElementById("change_location_map"), change_location_options);
            
    marker_array.push(new google.maps.Marker({
        position: change_location_latlng, 
        map: change_location_map, 
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
                        add_marker(entry);
                    });
                }
            });
        },
        success: function (data) {
            console.log(data);
        }
    });
    
    $('#change_location').click(function () {
        show_change_location_modal();
        return false;
    });
    
    $('#close_change_location').click(function () {
        hide_change_location_modal();
    });
    
}

function show_change_location_modal() {
    $('#change_location_content').show('fast', function() {
            google.maps.event.trigger(change_location_map, 'resize');
        });
}

function hide_change_location_modal() {
    $('#change_location_content').hide('fast');
}

function clear_markers(marker_array) {
    $.map(marker_array, function (entry) {
        entry.setMap(null);
    });
    marker_array = ([]);
}

function add_marker(data, marker_array) {
    var new_marker = new google.maps.Marker({
        map: change_location_map,
        position: new google.maps.LatLng({
            lat: data.geometry.location.Ha,
            lng: data.geometry.location.Ia
        }),
        title: data.name
    })
    marker_array.push(new_marker);
    console.log(data.geometry.location);
}