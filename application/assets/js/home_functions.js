// Vars
var myLatitude;
var myLongitude;
var myCity;
var current_day_offset = 0;
var map_tab_opened = false; // Used to resize the map the first time it's shown (Google fuck up)
var map;
var notification_timer;

// Run when the DOM is loaded.
$(function() {
    initialize_map();
    
    // Notifications badge (runs every 30 seconds)
    fetch_notifications();
    
    initialize_invite_people_form();
});

// Gets the notifications and set the timer again
function fetch_notifications() {
    $.get('/home/get_notification_popup', function(data){
        if(data > 0)
        {
            $('#notifications').badger(data);
        }
            
        notification_timer = setTimeout(fetch_notifications, 45000);
    });
}

// Gets the user's current location
// Calls the callback function on success with parameters of latitude and longitude
function get_current_location(callback) {
    if (navigator.geolocation) 
    {
        // Location callback
        navigator.geolocation.getCurrentPosition(function (position) {  
            var latitude=position.coords.latitude;
            var longitude=position.coords.longitude;
            if (callback != undefined) {
                callback(latitude, longitude);
            }
        },  function () {
            // Error callback
            alert('Your position is unavailable at this time.');
        });
    }
}

// Initializes the map and updates the user's location with the server
function initialize_map() {
    get_current_location(function(latitude, longitude) {
        // Update the user's profile with the new information.
        $.get('/home/update_user_location', {
            'latitude': latitude,
            'longitude': longitude,
            auto: true
        }, function (data) {
            data = $.parseJSON(data);
            if (data.status == 'adjusted') {
                // Location automatically adjusted
                alert(data.text);
                
                // Assign the latitude and longitude vars
                myLatitude = latitude;
                myLongitude = longitude;
                
                // Upload the city name
                get_current_city_name(function() {
                    $.get('/home/update_user_location', {
                        'city': myCity
                    });
                    
                    $('#using_location').html('Using location: ' + myCity);
                });
                
                // Map the user's position and show the map
                map_user_position();
                show_data_container('#map_content');
            } else if (data.status == 'from_profile') {
                // Assign the longitude, latitude, and city coordinates from the server to the js variables
                myLatitude = parseFloat(data.loc[0]);
                myLongitude = parseFloat(data.loc[1]);
                myCity = data.city_state;
                
                $('#using_location').html('Using location: ' + myCity);
                map_user_position();
            } else if (data.status == 'silent') {
                // Just get the city from the server
                myCity = data.city_state;
                $('#using_location').html('Using location: ' + myCity);
                
                map_user_position();
            }
        });
                
        // Create the map
        var map_options = {
            zoom: 14,
            center: new google.maps.LatLng(latitude,longitude),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        console.log('map_created');
        map = new google.maps.Map(document.getElementById("map"), map_options);
        
        // Attach and assign the back to info button
        $('#map').append($('<div class="back_to_info">Back to info</div>'));
        $('.back_to_info').click(function() {
            show_data_container('#info_content');
        });
    });
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

// Update myCity based off the user's coordinates.
function get_current_city_name(callback) {
    var geocoder = new google.maps.Geocoder();
    var request = {
        location: new google.maps.LatLng(myLatitude, myLongitude)
    }
    
    geocoder.geocode(request, function (result, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            result = result[0].address_components;
            
            // Loop through each response entry and construct the date accordingly
            myCity = '';
            var types_index;
            $.each(result, function (array_index, element) {
                // Find the city
                types_index = $.inArray('locality', element.types);
                if (types_index != -1) {
                    myCity = result[array_index].long_name;
                }
                
                // Find the state
                types_index = $.inArray('administrative_area_level_1', element.types);
                if (types_index  != -1) {
                    myCity += ', ' + result[array_index].short_name;
                }
            });
            
            if (callback != undefined) {
                callback();
            }
        }
    });
}

// Displays the data panel within the wrapper
function show_data_container(data_div, callback) {
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
        if (callback != undefined) {
            callback();
        }
    }
}

// Shows the correct container and resizes the map.
function show_data_panel(data_div, callback) {
    // Show the appropriate container
    $(data_div).show('slide', {}, 'fast', function () {
        // If the map tab is opened, refresh the bounds
        if (data_div == '#map_content') {
            // Resize the map after the animation finishes to eliminate the missing tile errors.
            if (!map_tab_opened) {
                console.log('resized');
                google.maps.event.trigger(map, 'resize');
                map_tab_opened = true;
            }
            
            calculate_map_bounds();
            
            if (callback != undefined) {
                callback();
            }
        }
    });
}

// Allows people to invite people to PlanJar
function initialize_invite_people_form() {
    $('.bottom_right_section .in-field_block label').inFieldLabels();
    
    $('#invite_email_form').submit(function() {
        $('#invite_email').val($('#invite_email').val().replace(' ', ''));
        var reg_ex = /(([a-zA-Z0-9]){2,}@([a-zA-Z0-9]){2,}\.([a-zA-Z0-9]){2,},)*([a-zA-Z0-9]){2,}@([a-zA-Z0-9]){2,}\.([a-zA-Z0-9]){2,}/;
        if (!reg_ex.test($('#invite_email').val())) {
            alert('Make sure the email address(es) you entered are valid and comma-seperated.');
        } else {
            $.get('/home/invite_to_planjar', {
                'email_list': $('#invite_email').val()
            }, function() {
                $('#invite_email').val('');
                $('#invite_email').blur();
            });
        }
        
        return false;
    });
}