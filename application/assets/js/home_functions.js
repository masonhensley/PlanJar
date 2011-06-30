var myLatitude;
var myLongitude;
var current_day_offset = 0;

// Run when the DOM is loaded.
$(function() {
    // places map
    location_data();
});

var initialLocation;
var browserSupportFlag;
var map;

function location_data() {
    
    if (navigator.geolocation) 
    {
        navigator.geolocation.getCurrentPosition
        ( 
            function (position) 
            {  
                myLatitude=position.coords.latitude;
                myLongitude=position.coords.longitude;
                
                // Update the user's profile with the new information.
                $.get('/home/update_user_location', {
                    latitude: myLatitude,
                    longitude: myLongitude,
                    auto: true
                }, function (data) {
                    if (data != 'success') {
                        alert(data);
                    }
                });
                
                // Update the city name.
                update_current_city_name();
                
                mapThisGoogle(position.coords.latitude, position.coords.longitude);
            }, 
            // next function is the error callback
            function (error)
            {
                switch(error) {
                    case error.TIMEOUT:
                        alert ('Timeout');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert ('Position unavailable');
                        break;
                    case error.PERMISSION_DENIED:
                        alert ('Permission denied');
                        break;
                    case error.UNKNOWN_ERROR:
                        alert ('Unknown error');
                        break;
                }
            });
    }
}

// places the google map
function mapThisGoogle(latitude,longitude)
{
    var myLatlng = new google.maps.LatLng(latitude,longitude);
        
    var myOptions = {
        zoom: 14,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
        
    map = new google.maps.Map(document.getElementById("map"), myOptions);
            
    var temp_marker = new google.maps.Marker({
        position: myLatlng, 
        map: map, 
        draggable: true,
        title:"Your location!"
    });
    
    // Add the marker to the marker list.
    map_marker_array.push(temp_marker);
}

// populates the popular location main panel
function load_visible_plans(){
    $.get('/home/load_popular_locations', {
        'selected_groups': get_selected_groups(),
        'selected_day': get_selected_day()
    }, function (data) {
        $('#visible_plans_panel').html(data); 
    });
}

// Returns a list of selected groups.
function get_selected_groups() {
    var return_list = ([]);
    $('.selectable_group.selected_group').each(function (index, element) {
        return_list.push($(element).attr('group_id'));
    });
    return return_list;
}

// Gets the currently selected day offset (0-based)
function get_selected_day() {
    return $('.days_panel .day.day_selected').attr('day_offset');
}

// Return the city based off the user's coordinates.
function update_current_city_name() {
    var geocoder = new google.maps.Geocoder();
    var request = {
        location: new google.maps.LatLng(myLatitude, myLongitude)
    }
    
    geocoder.geocode(request, function (result, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            result = result[0].address_components;
            
            $('#using_location').html('Using location: ' + result[2].long_name + ', ' + result[5].short_name);
        }
    });
}

/* .....................Global functions and vars..................... */
// Keep track of all markers.
var map_marker_array = [];

// Remove all markers and updates the map accordingly.
function clear_map_markers () {
    $.map(map_marker_array, function (entry) {
        entry.setMap(null);
    });
    
    map_marker_array.length = 0;
}

function hide_data_containers() {
    $('.tab_bar .data_tab').removeClass('tab_selected');
    $('.data_container').hide();
}

function show_data_container(data_div) {
    console.log($('.tab_bar [assoc_div="' + data_div + '"]').hasClass('tab_selected'));
    if (!$('.tab_bar [assoc_div="' + data_div + '"]').hasClass('tab_selected')) {
        hide_data_containers();
        $('.tab_bar [assoc_div="' + data_div + '"]').addClass('tab_selected');
        $(data_div).show('clip', {}, 'fast', function () {
            console.log('resized');
            google.maps.event.trigger(map, 'resize');
        });
    }
}