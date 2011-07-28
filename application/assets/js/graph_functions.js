// -------------------------------------------------- group data box view --------------------------------------------------
function populate_sex_info(container, data) {
    var j_first_container = $(container + ' div:first');
    var d_first_container = d3.select(container + ' div').node();
    
    // Add the squares
    for (var i = 0; i < 10; ++i) {
        j_first_container.append('<div class="sex_box"></div>');
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