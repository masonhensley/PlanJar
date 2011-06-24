// Perform all change of location modal initialization
function initialize_change_location_modal() {
    // Initialize the dialog.
    $('#change_location_content').dialog(
    {
        autoOpen: false,
        width: 600,
        height: 500,
        resizable: false,
        show: function (event, ui) {
            $(this).show('clip', function() {
                alert('complete');
                change_location_map.checkResize();
            })
        },
        hide: 'explode'
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
            }
            
            var places_service = new google.maps.places.PlacesService(change_location_map);
            places_service.search(places_request, function (results, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    console.log(results);
                    $.map(results, function (entry) {
                        console.log(entry);
                    })
                    
                    
                    for (var i = 0; i < results.length; i++) {
                        var place = results[i];
                    //createMarker(results[i]);
                        
                    }
                }
            });
        },
        success: function (data) {
            console.log(data);
        }
    });
    
    $('#change_location').click(function () {
        $('#change_location_content').dialog('open');
        return false;
    });
}