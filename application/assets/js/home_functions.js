// Run when the DOM is loaded.
$(function() {
    //initialize();
    
    /* Instantiate jQuery Selectable */
    $('#test').selectable({
        selected: function (event, ui) {
            var e= $(ui.selected);
            if (e.hasClass('my-selected')) {
                e.removeClass('my-selected');
                e.removeClass('ui-selected');
            } else {
                e.addClass('my-selected');
                e.addClass('ui-selected');
            }
        },
        unselected: function (event, ui) {
            var e= $(ui.unselected);
            if (e.hasClass('my-selected')) {
                e.addClass('my-selected');
                e.addClass('ui-selected');
            } else {
                e.removeClass('ui-selected');
                e.removeClass('my-selected');
            }
        },
        unselecting: function (event, ui) {
            var e= $(ui.unselecting);
            if (e.hasClass('my-selected')) {
                e.addClass('ui-selected');
                e.addClass('my-selected');
            } else {
                e.removeClass('ui-selected');
                e.removeClass('my-selected');
            }
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
