// -------------------------------------------------- group data box view --------------------------------------------------

// Outputs ten boxes with the supplied percentage of them filled in
// Accepts 0 <= n <= 100
function populate_percentage_box(container, percentage, active_class, vertical) {
    // Create the bar div if it's not there
    if ($(container).children().length < 1) {
        // Compute the correct styles to add
        var style = "position: absolute; ";
        if (vertical) {
            style += "bottom: 0px; left: 0px;"
        } else {
            style += "left: 0px; top: 0px;"
        }
        $(container).append($('<div class="' + active_class + '" style="' + style + '"></div>'));
    }
    
    // Change the width or the height, accordingly
    var width_height;
    if (vertical) {
        width_height = 'height';
    } else {
        width_height = 'width';
    }
    
    // Define the bar width scaling function
    var bar_scale = d3.scale.linear()
    .range(['0%', '100%'])
    .domain(['0', '100']);
    
    // Select the bar
    d3.select(container).selectAll('div.percent_bar')
    // Add data to the bars
    .data([percentage])
    // Set the width according to the input data
    .style(width_height, function (d) {
        return bar_scale(d) + '%';
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
        var sel_date = Date.parse(selected_date);
        var data_date = Date.parse(d.date);
        
        // Return true if the date matches the selected date
        return data_date.equals(sel_date);
    })
    // Toggle the current day class
    .classed('bar_today', function (d) {
        var cur_date = Date.parse('today');
        var data_date = Date.parse(d.date);
        
        // Return true if the date matches the current date
        return cur_date.equals(data_date);
    });
    
    // Select the chart bars
    d3.select(container).selectAll('div.graph_bar_label')
    // Add data to the bars
    .data(data)
    // Set the text (day of the week or Today)
    .text(function (d) {
        var cur_date = Date.parse('today');
        var given_date = Date.parse(d.date);
        
        if (cur_date.equals(given_date)) {
            return 'Today';
        } else {
            return given_date.toString('ddd');
        }
    });
}

// Populates the container with a two percentage bar
function two_percentage_bar(container, percentage_a, percentage_b, class_a, class_b, vertical) {
    // Add the divs if they don't exist
    if ($(container).children().length < 2) {
        // Clear the container
        $(container).html('');
            
        // Compute the correct styles to add
        var style_a = "position: absolute; ";
        var style_b = style_a;
        if (vertical) {
            style_a += "width: 100%; top: 0px;";
            style_b += "width: 100%; bottom: 0px;"
        } else {
            style_a += "height: 100%; left: 0px;";
            style_b += "height: 100%; right: 0px;"
        }
            
        // Add the a div
        $(container).append($('<div class="' + class_a + ' bar_a" style="' + style_a + '"></div>'));
            
        // Add the b div
        $(container).append($('<div class="' + class_b + ' bar_b" style="' + style_b + '"></div>'));
    }
    
    // Change the width or the height, accordingly
    var width_height;
    if (vertical) {
        width_height = 'height';
    } else {
        width_height = 'width';
    }
    
    // Select the left div
    d3.select(container).selectAll('.bar_a')
    // Add data
    .data([percentage_a])
    // Set the width
    .style(width_height, function (d) {
        return d + '%';
    });
    
    // Select the right div
    d3.select(container).selectAll('.bar_b')
    // Add data
    .data([percentage_b])
    // Set the width
    .style(width_height, function (d) {
        return d + '%';
    });
}