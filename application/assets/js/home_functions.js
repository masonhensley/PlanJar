// Run when the DOM is loaded.
$(function() {
    //initialize();
    
    $('#test').selectable({
        selected: function(event, ui) {
            alert($(ui.selected).hasClass('click-selected'))
        }
        /*
            if ($(ui.selected).hasClass('click-selected')) {
                $(ui.selected).removeClass('ui-selected click-selected');

            } else {
                $(ui.selected).addClass('click-selected');

            }
        */
        },
        unselected: function (event, ui) {
            /*
            $(ui.unselected).removeClass('click-selected');
            */
        }
    });
});



	

var initialLocation;
var browserSupportFlag;

function initialize() {
    var map_options = {
        zoom: 14,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), map_options);
  
    // Try W3C Geolocation
    if(navigator.geolocation) {
        browserSupportFlag = true;
        navigator.geolocation.getCurrentPosition(function(position) {
            initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
            map.setCenter(initialLocation);
        }, function() {
            handleNoGeolocation(browserSupportFlag);
        });
    // Browser doesn't support Geolocation
    } else {
        browserSupportFlag = false;
        handleNoGeolocation(browserSupportFlag);
    }
  
    function handleNoGeolocation(errorFlag) {
        if (errorFlag == true) {
            alert("Geolocation service failed.");
        //initialLocation = newyork;
        } else {
            alert("Your browser doesn't support geolocation. We've placed you in Siberia.");
        //initialLocation = siberia;
        }
    //map.setCenter(initialLocation);
    }
}
