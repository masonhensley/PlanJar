$(function() {
    $( "#plans" ).tabs({
        select: pull_plan_data
    });
});

function pull_plan_data(event, ui){
    $.get('/home/get_plan_data', {
                'plan_selected': $(ui.item).attr('plan_id')
            }, function (data) {
                alert(data);
            });

            $("#map_data_tabs").tabs("select","#data_tab");
}