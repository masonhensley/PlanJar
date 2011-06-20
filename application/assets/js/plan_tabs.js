$(function() {
    $( "#plans" ).tabs({
        select: get_plan_data
    });
});

function get_plan_data(event, ui){
    
    $.get('/home/get_plan_data', {
        'plan selected': $('#plans .ui-state-active a').attr('plan_id')
    }, function (data) {
        // Replace the data and show the data tab.
        $('#data_tab').html(data)
        $("#map_data_tabs").tabs('select', '#data_tab');
    });
    
    
    $("#map_data_tabs").tabs("select","#data_tab");
    $('#data_tab').html();   
}