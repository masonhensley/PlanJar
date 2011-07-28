// -------------------------------------------------- group data box view --------------------------------------------------

// Outputs ten boxes with the supplied percentage of them filled in
// Accepts 0 <= n <= 1
function populate_percentage_box(container, percentage) {
    // Create the marker boxes if they aren't there
    if ($(container).children().length < 10) {
        // Clear the container and add the boxes
        $(container).html('');
        
        var marker_box;
        var half_marker_box = $('<div class="half_marker_box"></div>');
    
        // Loop through each marker box
        for (i = 0; i < 10; ++i) {
            // Create a marker box
            marker_box = $('<div class="marker_box"></div>');
        
            // Append the half marker boxes
            half_marker_box.appendTo(marker_box);
            half_marker_box.appendTo(marker_box);
        
            // Append the marker box to its wrapper
            marker_box.appendTo(container);
        }
    
        // Create an array with an entry for each half box
        var d3_data = [];
        for (var i = 0; i < 20; ++i) {
            d3_data.push(i < percentage * 20);
        }
    }
     
    // Select all half boxes
    console.log(container);
    d3.select(container).selectAll('.half_marker_box')
    // Add the data to the selection
    .data(d3_data)
    // Set the background to purple if necessary
    .style('background-color', function (d) {
        if (d) {
            return 'purple'
        } else {
            return 'white'
        }
    });
}

// Populates the container with a vertical bar graph
// x = day, y = plan count
function populate_day_graph(container, data) {
    // Select the chart bars (they don't exist yet)
    var bars = d3.select(container).selectAll('div.graph_bar')
    // Add data to the bars
    .data(data)
    // Instantiate enough elements to match the data count
    .enter().append('div').classed('graph_bar', true)
    // Set the height according to the input data
    .style('height', function (d) {
        return d.count * 10 + 'px';
    });
}