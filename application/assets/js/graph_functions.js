// -------------------------------------------------- group data box view --------------------------------------------------
function populate_sex_info(container, data) {
    var j_first_container = $(container + ' div:first');
    var d_first_container = d3.select(container + ' div').node();
    
    // Add the lower markers
    var j_wrapper = $('<div class="sex_box_wrapper"></div>');
    for (var i = 0; i < 10; ++i) {
        j_wrapper.append('<div class="sex_box"></div>');
    }
    j_wrapper.find('.sex_box').css('background-color', '#480085');
    j_wrapper.appendTo(j_first_container);
    
    
    var graph_data = [.5];
    
    
    // Add the upper markers
    j_wrapper = $('<div class="sex_box_wrapper"></div>');
    for (i = 0; i < 6; ++i) {
        j_wrapper.append('<div class="sex_box"></div>');
    }
    j_wrapper.find('.sex_box').css('background-color', 'purple');
    j_wrapper.appendTo(j_first_container);
}