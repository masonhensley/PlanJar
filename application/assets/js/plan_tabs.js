$(function() {
    initialize_plan_panel();
});

function initialize_plan_panel(){
    $('div.plans_wrapper li').click(function() {
        
        // Make the list tiems togglable.
        if ($(this).hasClass('plan_content')) {
            $('.plan_content').removeClass('selected_plan');
            $(this).addClass('selected_plan');
        }
        
        // fetch the data about the plan and display it in the data div
        $.get('/home/get_plan_data', {
            'plan_selected': $('.selected_plan').attr('plan_id')
        }, function (data) {
                
            // Replace the data and show the data tab.
            $('#data_tab').html(data);
                
            // select the data tab
            if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
                $("#map_data_tabs").tabs('select', '#data_tab');
            }
        }); 
    });    
}

// Populates the plan panel.
function populate_plan_panel() {
    $.get('/home/get_my_plans', function (data) {
        $('div.plans_wrapper').html(data);
    });
}