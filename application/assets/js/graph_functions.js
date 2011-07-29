// -------------------------------------------------- group data box view --------------------------------------------------

// Outputs ten boxes with the supplied percentage of them filled in
// Accepts 0 <= n <= 1
function populate_percentage_box(container, percentage) {
    // Create the marker boxes if they aren't there
    if ($(container).children().length < 10) {
        // Clear the container and add the boxes
        $(container).html('');
        
        var marker_box;
        // Loop through each marker box
        for (i = 0; i < 10; ++i) {
            // Create a marker box
            marker_box = $('<div class="marker_box"></div>');
        
            // Append the half marker boxes
            marker_box.append($('<div class="half_marker_box"></div>'));
            marker_box.append($('<div class="half_marker_box"></div>'));
        
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
function populate_day_graph(container, data, selected_date) {
    // Create the bars and labels if they aren't there
    if ($(container).children().length != data.length) {
        // Clear the container
        $(container).html('');
        
        // Loop through each bar/label wrapper box to be created
        var vert_bar_wrapper;
        for (i = 0; i < data.length; ++i) {
            // Store the wrapper
            vert_bar_wrapper = $('<div class="vert_bar_wrapper"></div');
            
            // Append a bar div
            vert_bar_wrapper.append($('<div class="graph_bar"></div>'));
            
            // Append a label div
            vert_bar_wrapper.append($('<div class="graph_bar_label"></div>'));
            
            // Append the resulting wrapper to the container
            $(container).append(vert_bar_wrapper);
        }
    }
    
    // Extract the plan counts from the data so that we can get the max with D3
    var plan_counts = $.map(data, function (item) {
        return item.count;
    });
    
    // Define the bar height scaling function
    var bar_scale = d3.scale.linear()
    .domain([0, d3.max(plan_counts)])
    .range(['16px', '100px']);
    
    // Select the chart bars
    d3.select(container).selectAll('div.graph_bar')
    // Add data to the bars
    .data(data)
    // Set the height according to the input data
    .style('height', function (d) {
        return bar_scale(d.count);
    })
    // Set the number of plans text
    .text(function (d) {
        return d.count;
    })
    // Toggle the selected day class
    .classed('bar_selected_day', function (d) {
        var sel_date = new Date(selected_date);
        var data_date = new Date(d.date);
        
        // Return true if the date matches the selected date
        return data_date.toString().substr(0, 15) == sel_date.toString().substr(0, 15);
    })
    // Toggle the current day class
    .classed('bar_today', function (d) {
        var cur_date = new Date();
        var data_date = new Date(d.date);
        
        // Return true if the date matches the current date
        return cur_date.toString().substr(0, 15) == data_date.toString().substr(0, 15);
    });
    
    // Select the chart bars
    d3.select(container).selectAll('div.graph_bar_label')
    // Add data to the bars
    .data(data)
    // Set the text (day of the week or Today)
    .text(function (d) {
        var cur_date = new Date();
        var given_date = new Date(d.date);
        var day_array = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        
        if (cur_date.toString().substr(0, 15) == given_date.toString().substr(0, 15)) {
            return 'Today';
        } else {
            return day_array[given_date.getDay()];
        }
    });
}