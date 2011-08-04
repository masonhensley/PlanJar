$(function() {
    initialize_plan_panel();
});

// Populates the plan panel
function populate_plan_panel() {
    $.get('/home/get_my_plans', function (data) {
        $('div.plans_wrapper').html(data);
        initialize_plan_panel();
    });
}

// Sets up the plan panel
function initialize_plan_panel(){
    // Click handler
    $('div.plan_content').click(function() {
        if(!$(this).hasClass('selected_plan'))
        {
            // No plan selected. Deselect all controlls
            deselect_all_controlls();
            
            // Select this plan
            $(this).addClass('selected_plan');
            
            get_plan_data();
            show_data_container('#info_content');
        }else{
            $(this).removeClass('selected_plan');
            $('#info_content').html('<img src="/application/assets/images/center_display.png">');
        }
    });   
}

// fetch the data about the plan and display it in the plan data div
function get_plan_data() {
    $.get('/home/load_selected_plan_data', {
        'plan_selected': $('.selected_plan').attr('plan_id')
    }, function (data) {
        data = $.parseJSON(data);
        
        // Replace the data and show the data tab.
        $('#info_content').html(data.html);
        
        // Handles clicking on the delete plan button
        $('.delete_plan').confirmDiv(function (clicked_elem) {
            $.get('/home/delete_plan', {
                'plan_selected': $('.selected_plan').attr('plan_id')
            }, function (data) {
                // Replace the data and show the data tab.
                $('#info_content').html(data);
                populate_plan_panel();
            });
        });
        
        // Handles clicking on invite people
        $('.invite_people').click(function () {
            open_invite_modal('event', data.event_id, data.privacy, data.originator);
        });
    });
}