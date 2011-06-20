$(function() {
    $( "#plans" ).tabs({
        select: pull_plan_data
    });
});

function pull_plan_data(event, ui){
    $.get('/home/get_plan_data', {
        'plan_selected': ui.item.plan_id
    }, function (data) {
        // Replace the data and show the data tab.
        $('#data_tab').html(data)
        if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
            $("#map_data_tabs").tabs('select', '#data_tab');
        }
    });
            
    return false;
}