// -------------------------------------------------- group data box view --------------------------------------------------

// Outputs ten boxes with the supplied percentage of them filled in
function populate_percentage_box(container, data) {
    // Vars needed to construct a set of marker boxes in a wrapper
    var marker_wrapper = $('<div class="marker_wrapper"></div>');
    var marker_box;
    var half_marker_box;
    var number_of_active_halves = Math.round(data * 20);
    
    // Loop through each marker box
    for (var i = 0; i < 10; ++i) {
        // Create a marker box
        marker_box = $('<div class="marker_box"></div>');
        
        // Append the first half marker box and change color if necessary
        half_marker_box = $('<div class="half_marker_box"></div>');
        if (number_of_active_halves > 0) {
            half_marker_box.css('background-color', 'purple');
            --number_of_active_halves;
        }
        half_marker_box.appendTo(marker_box);
        
        // Append the second half marker box and change color if necessary
        half_marker_box = $('<div class="half_marker_box"></div>');
        if (number_of_active_halves > 0) {
            half_marker_box.css('background-color', 'purple');
            --number_of_active_halves;
        }
        half_marker_box.appendTo(marker_box);
        
        // Append the marker box to its wrapper
        marker_box.appendTo(marker_wrapper);
    }
    
    // Append the newly created wrapper to the supplied container
    console.log($(container));
    marker_wrapper.appendTo(container);
}