$(function() {
    initialize_plan_panel();
});

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
            // Deselect this plan
            $(this).removeClass('selected_plan');
        }
        
        // Re-populate (clear) the popular locations panel
        populate_popular_locations();
        
        // DIsplay the info box
        display_info();
    });   
}

// Populates the plan panel (panel is pre-populated in PHP)
function populate_plan_panel() {
    $.get('/home/get_my_plans', function (data) {
        $('div.plans_wrapper').html(data);
        initialize_plan_panel();
    });
}