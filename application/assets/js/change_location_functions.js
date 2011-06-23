function initialize_change_location_modal() {
    $('#change_location_content').dialog(
    {
        autoOpen: false,
        width: 600,
        height: 500,
        resizable: false,
        show: 'clip',
        hide: 'explode'
    });
    
    // Set up the in-field labels.
    $('#change_location_content label').inFieldLabels();
    
    // Current location
    var change_location_latlng = new google.maps.LatLng(myLatitude, myLongitude);
    
    // Set up the autocomplete.
    $('#change_location_search').autocomplete({
        minLength: 2,
        source: function (request, response) {
            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/place/autocomplete/json',
                dataType: 'jsonp',
                data: {
                    input: request.term,
                    sensor: false,
                    key: 'AIzaSyCYUQ0202077EncqTobwmahQzAY8DwGqa4',
                    location: change_location_latlng,
                    jsonp: 'json'
                }
            });
        },
        success: function (data) {
            console.log(data);
        }
    });
    
    // Set up the map.
    var change_location_options = {
        zoom: 13,
        center: change_location_latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
        
    var change_location_map = new google.maps.Map(document.getElementById("change_location_map"), change_location_options);
            
    var new_location_marker = new google.maps.Marker({
        position: change_location_latlng, 
        map: change_location_map, 
        draggable: true,
        title:"Your location!"
    });
    
    $('#change_location').click(function () {
        $('#change_location_content').dialog('open');
        return false;
    });
}