$(function() {
    $( "#plans" ).tabs({
        select: function(event, ui){
         
            $.get('/home/get_plan_data', {
                'plan_selected': $(ui.tab.id)
            }, function (data) {
                // Replace the data and show the data tab.
                alert(data);
                $('#data_tab').html(data);
                if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
                    $("#map_data_tabs").tabs('select', '#data_tab');
                }
            });
        
        }
    });
});

function pull_plan_data(event, ui){
    
}