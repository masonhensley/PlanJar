// -------------------------------------------------- group data box view --------------------------------------------------

// Outputs ten boxes with the supplied percentage of them filled in
// Accepts 0 <= n <= 1
function populate_percentage_box(container, data) {
    // Vars needed to construct a set of marker boxes
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
        marker_box.appendTo(container);
    }
}

// Populates the container with a vertical bar graph
// x = day, y = plan count
function populate_day_graph(container, data) {
    // Select the chart bars (they don't exist yet)
    d3.select(container).selectAll('div.graph_bar')
    // Add data to the bars
    .data(data)
    // Instantiate enough elements to match the data count
    .enter().append('div').classed('graph_bar')
    // Set the height according to the input data
    .style('height', function (d) {
        return d.count * 10 + 'px';
    });
}