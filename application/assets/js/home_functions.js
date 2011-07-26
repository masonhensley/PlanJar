var myLatitude;
var myLongitude;
var myCity;
var current_day_offset = 0;

// Used to resize the map the first time it's shown (Google fuck up)
var map_tab_opened = false;

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
                    data = $.parseJSON(data);
                    
                    console.log(data);
                    console.log(data.status);
                    if (data.status == 'adjusted') {
                        // Location automatically adjusted
                        alert(data.text);
                        map_user_position();
                        show_data_container('#map_data');
                    } else if (data.status == 'from_profile') {
                        // Assign the longitude and latitude coordinates from the server to the js variables
                        myLatitude = parseInt(data.loc[0]);
                        myLongitude = parseInt(data.loc[1]);
                    }
                    map_user_position();
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
}

// Returns a list of selected groups.
function get_selected_groups() {
    var return_list = ([]);
    if(!$('.network_tab').hasClass('network_active'))
    {
        $('.selectable_group.selected_group').each(function (index, element) {
            return_list.push($(element).attr('group_id'));
        });
    }else if($('.network_tab').hasClass('network_active'))
    {
        return_list.push($('.network_active').attr('group_id'));
    }
    // if nothing is selected, you can check the php variable for (false)
    return return_list;
}

// Gets the currently selected day offset (0-based)
function get_selected_day() {
    return $('.days_panel .day.day_selected').attr('day_offset');
}

function get_selected_location(){
    return $('.selected_location').attr('place_id');
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
            
            myCity = result[2].long_name + ', ' + result[5].short_name;
            $('#using_location').html('Using location: ' + myCity);
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

// Hides all data containers
function hide_data_containers() {
    
    $('.tab_bar .data_tab').removeClass('tab_selected');
    $('.data_container:visible').hide('slide', {}, 'fast', function () {
        $('.data_container_wrapper').hide('blind', {}, 'fast');
    });
}

// Shows the data container specified in the argument (takes care of closing beforehand, too)
function show_data_container(data_div, callback) {
    // Make callback optional.
    if (callback == undefined) {
        callback = function() {};
    }
    
    $('.change_location_panel').hide(); // closes the 'change location' div that gets added in the map div
    
    // If no tab is selected, show the wrapper.
    if (!$('.tab_bar .data_tab').hasClass('tab_selected')) {
        $('.data_container_wrapper').show('blind', {}, 'fast', function () {
            show_data_wrapper(data_div, callback);
        });
    } else {
        show_data_wrapper(data_div, callback);
    }
}

// Displays the data panel within the wrapper
function show_data_wrapper(data_div, callback) {
    // Select the appropriate tab.
    $('.tab_bar .data_tab').removeClass('tab_selected');
    $('.tab_bar [assoc_div="' + data_div + '"]').addClass('tab_selected');
        
    // Only show a container if it's not already visible.
    if ($(data_div).css('display') == 'none') {
        if ($('.data_container:visible').length > 0) {
            // Hide any visible data containers.
            $('.data_container:visible').hide('slide', {}, 'fast', function() {
                show_data_panel(data_div, callback);
            });
        } else {
            show_data_panel(data_div, callback);
        }
    } else {
        callback();
    }
}

// Shows the correct container and resizes the map.
function show_data_panel(data_div, callback) {
    // Show the appropriate container
    $(data_div).show('slide', {}, 'fast', function () {
        callback();
        
        // Resize the map after the animation finishes to eliminate the missing tile errors.
        if (!map_tab_opened) {
            google.maps.event.trigger(map, 'resize');
            map_tab_opened = true;
        }
    //map_user_position();
    });
}

// Puts the user's position on the map and centers to it.'
function map_user_position() {
    clear_map_markers();
    
    map_marker_array.push(new google.maps.Marker({
        position: new google.maps.LatLng(myLatitude, myLongitude),
        map: map,
        title: 'Your location'
    }));
    
    map.setCenter(new google.maps.LatLng(myLatitude, myLongitude));
    map.setZoom(14);
}

