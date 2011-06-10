var myLatitude;
var myLongitude;
var myCity;
var myAddress;

// Run when the DOM is loaded.
$(function() {
    
    // places map
    location_data();
   
    $( "#tabs" ).tabs({
        collapsible: true
    })

    $( ".tabs-bottom .ui-tabs-nav, .tabs-bottom .ui-tabs-nav > *" )
    .removeClass( "ui-corner-all ui-corner-top" )
    .addClass( "ui-corner-bottom" );
    
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
    
    // Initialize the group buttonset (select one/select multiple).
    $('div .radio').buttonset();
    
    // Start the plan dialog box closed.
    $('#plan_content').dialog({
        autoOpen: false,
        width: 700,
        height: 300,
        resizable: false,
        show: 'clip',
        hide: 'explode'
    });
    
    // Initialize the make-a-plan modal.
    $('#make_a_plan').click(function() {
        $('#plan_content').dialog('open');
        
        // Initialize the in-field labels.
        $('#plan_content div.in-field_block label').inFieldLabels();
        
                
        // Initialize the plan location autocomplete instance.
        $('#plan_location').autocomplete({
            minLength: 2,
            // Get info from the server.
            source: function (request, response) {
                $.get('/home/find_places', {
                    needle: request.term,
                    latitude: myLatitude,
                    longitude: myLongitude
                }, function (data) {
                    if (data == 'error') {
                        alert(data);
                        return;
                    }
                    // Convert each item in the JSON from the server to the required JSON
                    // form for the autocomplete and pass the result through the response
                    // handler.
                    alert('data' + data);
                    data = $.parseJSON(data);
                    response($.map(data, function (item) {
                        return {
                            label: item.name + ' (' + item.category + ')' + ' - ' + parseFloat(item.distance).toFixed(2) + "mi", 
                            value: item.name,
                            id: item.id
                        };
                    }));
                
                });
            },
            // When an item is selected, update the location text as well as the hidden
            // id field.
            select: function (event, ui) {
                $('#plan_location').val(ui.item.value);
                $('#plan_location_id').val(ui.item.id);
            }
        });
        
        // Initialize the plan category autocomplete instance.
        $('#plan_category').autocomplete({
            // Get info from the server.
            source: function (request, response) {
                $.get('/home/find_plan_categories', {
                    needle: request.term
                }, function (data) {
                
                    // Convert each item in the JSON from the server to the required JSON
                    // form for the autocomplete and pass the result through the response
                    // handler.
                    data = $.parseJSON(data);
                    response($.map(data, function (item) {
                        return {
                            label: item.category,
                            value: item.category,
                            id: item.id
                        };
                    }));
                
                });
            },
            // When an item is selected, update the location text as well as the hidden
            // id field.
            select: function (event, ui) {
                $('#plan_category').val(ui.item.value);
                $('#plan_category_id').val(ui.item.id);
            }
        });
        
        return false;
    });
    
// End of ready function.
});

// Should be called when #sel_one or #sel_mult
// Set up the Selectable instance with "standard" options or toggle options.
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

function location_data() {
    
    if (navigator.geolocation) 
    {
        navigator.geolocation.getCurrentPosition
        ( 
            function (position) 
            {  
                myLatitude=position.coords.latitude;
                myLongitude=position.coords.longitude;
                
                mapThisGoogle(position.coords.latitude, position.coords.longitude);
            }, 
            // next function is the error callback
            function (error)
            {
                switch(error.code) 
                {
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
            }
            );
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
    // be sure to include the script to initialize Google or Yahoo! Maps
 
    function mapThisGoogle(latitude,longitude)
    {
        var myLatlng = new google.maps.LatLng(latitude,longitude);
        
        var myOptions = {
            zoom: 14,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        
        var map = new google.maps.Map(document.getElementById("map"), myOptions);
            
        your_location_marker = new google.maps.Marker({
            position: myLatlng, 
            map: map, 
            draggable: true,
            title:"Your location!"
        });
        
        alert('fyc,n');
        $.ajax({    
            url: 'https://maps.googleapis.com/maps/api/place/search/json',
            data: {
                location: latitude + ',' + longitude,
                radius: 2000,
                name: 'dan',
                sensor: false,
                key: 'AIzaSyCYUQ0202077EncqTobwmahQzAY8DwGqa4'
            },
            type: 'get',
            crossDomain: true,
            dataType: 'json',
            success: function (data) {
                alert('data: ' + data.success);
            },
            error: function (data) {
            }
        });
        alert('after get');

    }
}
