$(function() {
    $( "#plans" ).tabs({
        select: function(event, ui){
            
            $.get('/home/get_plan_data', {
                'plan_selected': $('#plans .ui-state-active a').attr('plan_id')
            }, function (data) {
                alert(data);
            });

            $("#map_data_tabs").tabs("select","#data_tab");
        }
    });
});

function pull_plan_data(event, ui){
    
}