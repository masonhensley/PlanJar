$(function () {
    initialize_add_location_modal();
});

var new_location_map;
var new_location_marker;

// Initializes the add location modal
function initialize_add_location_modal() {
    // Close click handler
    $('#close_add_location').click(function () {
        // Clear and unfocus the text inputs
        $('#new_location_name, #new_location_category, #new_location_category_id').val('');
        $('#new_location_name, #new_location_category, #new_location_category_id').blur();
        
        // Hide the modal
        $('#add_location_modal').hide('fast');
    });
    
    // Draggable
    $('#add_location_modal').draggable({
        handle: '.title_bar'
    });
    
    // In-field labels
    $('#add_location_modal .in-field_block label').inFieldLabels();
    
    // Autocomplete
    $('#new_location_category').autocomplete({
        source: function (request, response) {
            // Get categories from the server
            $.get('/home/search_place_categories', {
                needle: request.term
            }, function (data) {
                data = $.parseJSON(data);
                
                // Map and return the results
                response($.map(data, function (item) {
                    return {
                        id: item.id,
                        label: item.category,
                        value: item.category
                    }
                }));
            });
        },
        select: function (event, ui) {
            $('#new_location_category_id').val(ui.item.id);
        }
    });
    
    // Number regular expression
    var number_exp = /^-?[0-9]+(\.[0-9]+)?$/;
    
    // Submit handler
    $('#submit_location').click(function () {
        if ($('#new_location_name').val().length < 3) {
            // Minimum length not met
            alert('Group names must be at least 3 characters long.');
        } else if ($('#new_location_id').val() == '') {
            // No category selected
            alert('You need to select a category from the autocomplete.')
        } else if (!number_exp.test($('#new_location_latitude').val()) || !number_exp($('#new_location_longitude').val())) {
            // Bad coordinate(s)
            alert('Your group coordinates seem to be off. Try dragging the marker.');
        } else {
            // Success
            $.get('/home/add_location?' + $('#new_location_form').serialize(), function (data) {
                data = $.parseJSON(data);
                
                // Populate the correct fields in the plan modal and hide this one
                $('#plan_location').val(data.name);
                $('#plan_location_id').val(data.id);
                $('#close_add_location').click();
            });
        }
    });
        
    // Select all when clicked
    $('#new_location_latitude, #new_location_longitude, #new_location_category').click(function () {
        $(this).focus();
        $(this).select();
    });
    
    // Coordinate location change handler
    $('#new_location_latitude, #new_location_longitude').keydown(function () {
        // Clear the box if bad in put is received
        if (!number_exp.test($(this).val())) {
            $(this).val('');
        }
    });
    
    // Manual location changes
    $('#new_location_latitude, #new_location_longitude').change(function () {
        // Replace the marker
        new_marker($('#new_location_latitude').val(), $('#new_location_longitude').val());
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
        new_marker(myLatitude, myLongitude);
        
        // Populate the boxes with the current location
        $('#new_location_latitude').val(myLatitude);
        $('#new_location_longitude').val(myLongitude);
    });
}

// Replaces the old marker with a new one at the given coordinates
function new_marker(latitude, longitude) {
    // Remove the old marker if it exists
    if (new_location_marker != null) {
        new_location_marker.setMap(null);
    }
    
    // Add the marker
    new_location_marker = new google.maps.Marker({
        position: new google.maps.LatLng(latitude, longitude),
        map: new_location_map,
        icon: 'http://www.google.com/mapfiles/arrow.png',
        draggable: true,
        title: 'Drag me'
    });
        
    // Assign the click event.
    google.maps.event.addListener(new_location_marker, 'drag', function (mouse_event) {
        // Update the coordinate boxes
        $('#new_location_latitude').val(mouse_event.latLng.lat());
        $('#new_location_longitude').val(mouse_event.latLng.lng());
    });
    
    // Center the map
    new_location_map.setCenter(new google.maps.LatLng(latitude, longitude));
}