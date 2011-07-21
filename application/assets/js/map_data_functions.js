$(function() {
    initialize_map_data_tabs();
})

// Initializes the map/data tabs.
function initialize_map_data_tabs() {
    // Initial select
    show_data_container('#group_data');
                
    // Click handler.
    $('div.tab_bar .data_tab').click(function () {
        if ($(this).hasClass('tab_selected')) {
            hide_data_containers();
        } else {
            show_data_container($(this).attr('assoc_div'))
        }
    });
}