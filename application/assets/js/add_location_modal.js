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
    $('#close_add_location').draggable({
        handle: '.title_bar'
    });
    
    // Create the map
    var map_options = {
        zoom: 14,
        center: new google.maps.LatLng(myLatitude,myLongitude),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    new_location_map = new google.maps.Map(document.getElementById("new_location_map"), map_options);
}

// Opens the add location modal
function show_add_location_modal() {
    console.log('in func');
    $('#add_location_modal').show('fast', function () {
        // Add the marker
        new_location_marker = new google.maps.Marker({
            position: new google.maps.LatLng(myLatitude, myLongitude),
            map: new_location_map,
            icon: 'http://www.google.com/mapfiles/arrow.png'
        });
        
        // Resize the map
        google.maps.event.trigger(new_location_map, 'resize');
        
        // Center the map
        new_location_map.setCenter(new google.maps.LatLng(myLatitude, myLongitude));
        //new_location_map.setZoom(14);
    });
}