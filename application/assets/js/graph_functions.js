// -------------------------------------------------- group data box view --------------------------------------------------
function populate_sex_info(container, data) {
    var j_first_container = $(container + ' div:first');
    var d_first_container = d3.select(container + ' div').node();
    var first_container_width = j_first_container.width();
    
    // Add the squares
    for (var i = 0; i < 10; ++i) {
        j_first_container.append('<div class="sex_box"></div>');
    }
    
    // Add the overlay
    j_first_container.append('<div class="sex_overlay"></div>');
    
    var graph_data = [.75];
    
    var d_overlay = d3.select(container + ' div.sex_overlay');
    d_overlay.style("width", function(graph_data) {
        return first_container_width * (1-graph_data) + "px";
    });
}