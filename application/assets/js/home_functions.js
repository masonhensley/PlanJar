var myLatitude;
var myLongitude;
var myCity;
var myAddress;
var current_day_offset = 0;

// Run when the DOM is loaded.
$(function() {
    
    // places map
    location_data();
   
    $( "#weekdays" ).tabs({
        //collapsible: true
        });

    $( ".tabs-bottom .ui-tabs-nav, .tabs-bottom .ui-tabs-nav > *" )
    .removeClass( "ui-corner-all ui-corner-top" )
    .addClass( "ui-corner-bottom" );
    
    // Set up the day of the week tabs.
    $("ul.weekdays li:first").addClass("day_selected").show(); //Activate first tab

    //On Click Event
    $("ul.weekdays li").click(function() {

        $("ul.weekdays li.day_selected").removeClass("day_selected"); //Remove any "day_selected" class
        $(this).addClass("day_selected"); //Add "day_selected" class to selected tab
        
        // Call the callback function.
        on_day_change($('ul.weekdays li.day_selected a').attr('href'));

        return false;
    });
    
// End of ready function.
});

function on_day_change(day_index) {
    current_day_offset = day_index;
}


// This function takes the user's latitude and longitude and passes them
// to the yahoo reverse geocoding api and returns JSON encoded string of
// relevent info.  This is saved in global variables at top.'
function reverse_geocode_user()
{
    $.ajax({
        url: 'http://where.yahooapis.com/geocode',
        data: {
            location: myLatitude+'+'+myLongitude,
            appid: '5CXRiH44',
            flags: 'J'
        },
        dataType: 'jsonp',
        success: function(data) {
            alert(data);
        }
    });
}

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
                
                // sets variables myCity, myAddress
                reverse_geocode_user();
                
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

function mapServiceProvider(latitude,longitude)
{
    // querystring function from prettycode.org: 
    // http://prettycode.org/2009/04/21/javascript-query-string/
 
    if (window.location.querystring['serviceProvider']=='Yahoo')
    {
        mapThisYahoo(latitude,longitude);
    }
    else
    {
        mapThisGoogle(latitude,longitude);
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
            
    your_location_marker = new google.maps.Marker({
        position: myLatlng, 
        map: map, 
        draggable: true,
        title:"Your location!"
    });
}
