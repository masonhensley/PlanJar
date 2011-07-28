// -------------------------------------------------- group data box view --------------------------------------------------
function populate_sex_info(container, data) {
    var first_container = d3.select(container + ' div').node();
    var first_container_width = $(container + ' div:first').width();
    
    // Add the squares
    $(container);
    for (var i = 0; i < 20; ++i) {
        $(container).append('<div class="sex_box"></div>')
    }
    
//    var graph_data = [.75];
//        var chart = d3.select(".graph_data");
//        
//        chart.enter()
//        .append("div")
//        .style("width", function(graph_data) {
//            return graph_data * 10 + "px";
//        }).style("height", "17px")
//        .text(function(d) {
//            return d;
//        });
}