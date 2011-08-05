$(function () {
    initialize_add_location_modal();
});

var new_location_map;
var new_location_marker;

// Initializes the add location modal
function initialize_add_location_modal() {
    // Close click handler
    $('#close_add_location').click(function () {
        $('#add_location_modal').hide('fast');
    });
    
    // Draggable
    $('#add_location_modal').draggable({
        handle: '.title_bar'
    });
    
    // In-field labels
    $('#new_location_modal .in-field_block label').inFieldLabels();
    
    // Autocomplete
    $('#new_location_category').autocomplete({
        source: function (request, response) {
            // Get categories from the server
            $.get('/home/search_place_categories', {
                needle: request.term
            }, function (data) {
                console.log(data);
            });
        }
    });
}

// Opens the add location modal
function show_add_location_modal() {
    $('#add_location_modal').show('fast', function () {
        // Create the map if it doesn't exist'
        if (new_location_map == undefined) {
            var map_options = {
                zoom: 14,
                center: new google.maps.LatLng(myLatitude,myLongitude),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            new_location_map = new google.maps.Map(document.getElementById("new_location_map"), map_options);
        }
    
        // Add the marker
        new_location_marker = new google.maps.Marker({
            position: new google.maps.LatLng(myLatitude, myLongitude),
            map: new_location_map,
            icon: 'http://www.google.com/mapfiles/arrow.png',
            draggable: true,
            title: 'Darg me'
        });
        
        // Center the map
        new_location_map.setCenter(new google.maps.LatLng(myLatitude, myLongitude));
        
        // Populate the boxes with the current location
        $('#new_location_latitude').val(myLatitude);
        $('#new_location_longitude').val(myLongitude);
        
        // Assign the click event.
        google.maps.event.addListener(new_location_marker, 'drag', function (mouse_event) {
            // Update the coordinate boxes
            $('#new_location_latitude').val(mouse_event.latLng.lat());
            $('#new_location_longitude').val(mouse_event.latLng.lng());
        });
    });
}