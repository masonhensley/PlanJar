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
        } else {
            $(this).removeClass('selected_plan');
        }
        
        display_info();
    });   
}

// fetch the data about the plan and display it in the plan data div
function get_plan_data() {
    
}