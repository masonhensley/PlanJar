// -------------------------------------------------- group data box view --------------------------------------------------
function populate_sex_info(container, data) {
    var first_container = d3.select(container + ' div:first');
    var first_container_width = $(container + ' div:first').width();
    
    var graph_data = [.75];
        var chart = d3.select(".graph_data");
        
        chart.enter()
        .append("div")
        .style("width", function(graph_data) {
            return graph_data * 10 + "px";
        }).style("height", "17px")
        .text(function(d) {
            return d;
        });
}