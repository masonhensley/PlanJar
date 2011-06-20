$(function() {
    $( "#plans" ).tabs({
        select: function(event, ui){
         
            $(this).addClass('active_plan');
         
            $.get('/home/get_plan_data', {
                'plan_selected': $('.active_plan').attr('id')
            }, function (data) {
                // Replace the data and show the data tab.
                alert(data);
                $('#data_tab').html(data);
                if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
                    $("#map_data_tabs").tabs('select', '#data_tab');
                }
            });
            
            $(this).removeClass('active_plan');
        
        }
    });
});

function pull_plan_data(event, ui){
    
}