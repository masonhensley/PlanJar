$(function() {
    initialize_map_data_tabs();
})

// Initializes the map/data tabs.
function initialize_map_data_tabs() {
    $("#map_data_tabs").tabs({
        
    });

    // Make the tabs go on the bottom.
    $("#map_data_tabs .tabs-bottom .ui-tabs-nav, #map_data_tabs .tabs-bottom .ui-tabs-nav > *")
    .removeClass("ui-corner-all ui-corner-top")
    .addClass("ui-corner-bottom");
}