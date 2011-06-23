$(function () {
    initialize_change_location_modal();
})

function initialize_change_location_modal() {
    $('#change_location_content').dialog(
    {
        autoOpen: false,
        width: 600,
        height: 250,
        resizable: false,
        show: 'clip',
        hide: 'explode'
    });
    
    // Set up the map.
    var change_location_latlng = new google.maps.LatLng(myLatitude, myLongitude);
    
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