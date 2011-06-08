// Run when the DOM is loaded.
$(function() {
    //initialize();
    
    // Set up the day of the week tabs.
    $(".tab_content").hide(); //Hide all content
    $("ul.tabs li:first").addClass("active").show(); //Activate first tab

    //On Click Event
    $("ul.tabs li").click(function() {

        $("ul.tabs li").removeClass("active"); //Remove any "active" class
        $(this).addClass("active"); //Add "active" class to selected tab
        $(".tab_content").hide(); //Hide all tab content

        return false;
    });
    
    // Set up the Selectable instance with default options (the shown
    // options are to keep the last selected item from disappearing).
    $('#my_groups').selectable({
        selected: function(event, ui) {
            if ($(ui.selected).hasClass('group_label')) {
                // Disallow group label divs from being selected.
                $(ui.selected).removeClass('ui-selected');
            } else {
                $(ui.selected).addClass('my-selected');
            }
        },
        unselected: function(event, ui) {
            $(ui.unselected).removeClass('my-selected');
        },
        // Disallow group label divs from being selected.
        selecting: function(event, ui) {
            if ($(ui.selecting).hasClass('group_label')) {
                $(ui.selecting).removeClass('ui-selecting');
            }
        }
        
    });
    
    // Initialize the buttonset (select one/select multiple).
    $('#one_mult').buttonset();
});

function toggle_group_select() {
    if ($('#sel_one').attr('checked') != 'checked') {
        
        // Set up the Selectable instance with custom options.
        $('#my_groups').selectable('destroy');
        
        // The following instantiation was pulled from
        // http://forum.jquery.com/topic/ui-selectable-allow-select-multiple-without-lasso
        $('#my_groups').selectable({
            selected: function (event, ui) {
                var e= $(ui.selected);
                if (e.hasClass('group_label')) {
                    // Dissalow group label divs from being selected.
                    e.removeClass('ui-selected');
                } else {
                    if (e.hasClass('my-selected')) {
                        e.removeClass('my-selected');
                        e.removeClass('ui-selected');
                    } else {
                        e.addClass('my-selected');
                        e.addClass('ui-selected');
                    }
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
            // Disallow group label divs from being selected.
            selecting: function(event, ui) {
                if ($(ui.selecting).hasClass('group_label')) {
                    $(ui.selecting).removeClass('ui-selecting');
                }
            }
        });
    } else {
        
        // Set up the Selectable instance with default options (the shown
        // options are to keep the last selected item from disappearing.
        $('#my_groups').selectable('destroy');
        $('#my_groups').selectable({
            selected: function(event, ui) {
                if ($(ui.selected).hasClass('group_label')) {
                    // Dissalow group label divs from being selected.
                    $(ui.selected).removeClass('ui-selected');
                } else {
                    $(ui.selected).addClass('my-selected');
                }
            },
            unselected: function(event, ui) {
                $(ui.unselected).removeClass('my-selected');
            },
            // Disallow group label divs from being selected.
            selecting: function(event, ui) {
                if ($(ui.selecting).hasClass('group_label')) {
                    $(ui.selecting).removeClass('ui-selecting');
                }
            }
        });
    }
}



	

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
