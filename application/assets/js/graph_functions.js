// -------------------------------------------------- group data box view --------------------------------------------------

// Outputs ten boxes with the supplied percentage of them filled in
// Accepts 0 <= n <= 100
function populate_percentage_box(container, percentage, active_class) {
    percentage = percentage/100;
    
    // Create the bar div if it's not there'
    if ($(container).children().length < 1) {
        $(container).append($('<div class="percent_bar ' + active_class + '"></div>'));
    }
    
    // Define the bar height scaling function
    var bar_scale = d3.scale.linear()
    .range(['0%', '100%']);
    
    // Select the bar
    d3.select(container).selectAll('div.percent_bar')
    // Add data to the bars
    .data([percentage])
    // Set the width according to the input data
    .style('width', function (d) {
        return bar_scale(d);
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
    
    // Populates the container with a 
    function two_percentage_bar(container, left_percentage, left_class, right_class) {
        // Add the divs if they don't exist
        if ($(container).children().length < 2) {
            // Clear the container
            $(container).html('');
            
            // Add the right (background) div
            $(container).append('<div class="' + right_class + ' two_bar_right"></div>');
            
            // Add the left div
            $(container).append('<div class="' + left_class + ' two_bar_left"></div>');
        }
    }
    
    // Select the left div
    d3.select(container).selectAll('div.two_bar_left')
    // Add data
    .data([left_percentage])
    // Set the width
    .style('width', function (d) {
        return d + '%';
    });
}