// -------------------------------------------------- group data box view --------------------------------------------------
function populate_sex_info(container, data) {
    var j_first_container = $(container + ' div:first');
    var d_first_container = d3.select(container + ' div').node();
    
    // Create and add the marker boxes
    var j_wrapper = $('<div class="marker_wrapper"></div>');
    var marker_box;
    var half_marker_box;
    var number_of_active_halves = .75 * 20;
    
    for (var i = 0; i < 10; ++i) {
        // Create the marker box
        marker_box = $('<div class="marker_box"></div>');
        
        // Append the first marker box and change color if necessary
        half_marker_box = $('<div class="half_marker_box"></div>');
        if (number_of_active_halves > 0) {
            half_marker_box.css('background-color', 'purple');
            --number_of_active_halves;
        }
        half_marker_box.appendTo(marker_box);
        
        // Append the second marker box and change color if necessary
        half_marker_box = $('<div class="half_marker_box"></div>');
        if (number_of_active_halves > 0) {
            half_marker_box.css('background-color', 'purple');
            --number_of_active_halves;
        }
        half_marker_box.appendTo(marker_box);
        
        // Append the marker box to its wrapper
        marker_box.appendTo(j_wrapper);
    }
    
    // Append the newly created wrapper to the first container
    j_wrapper.appendTo(j_first_container);
}