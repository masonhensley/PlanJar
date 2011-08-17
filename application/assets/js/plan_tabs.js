$(function() {
    initialize_plan_panel();
});

// Sets up the plan panel
function initialize_plan_panel(){
    // Click handler
    $('.plan_content').click(function() {
        if(!$(this).hasClass('selected_plan'))
        {
            // No plan selected. Deselect all controlls
            deselect_all_controlls();
            
            // Select this plan
            $(this).addClass('selected_plan');
            
            // Populate the map
            $.get('/home/get_plans_coords', {
                plan_id: $(this).attr('plan_id')
            }, function(data) {
                console.log(data);
            });
        } else {
            // Deselect this plan
            $(this).removeClass('selected_plan');
        }
        
        // Display the info box
        display_info();
        
    // not sure how to make this be called after display_info() is done
    // setup the view attendees button
    }, function(){
        $('#view_attendees').click(function(){
            alert('hey');
            $.get('/home/attending_list', {
                plan_id : $('.selected_plan').attr('plan_id')
            });    
        });
    });
}

// Populates the plan panel (panel is pre-populated in PHP)
function populate_plan_panel(callback) {
    $.get('/home/get_my_plans', function (data) {
        $('div.plans_wrapper').html(data);
        
        initialize_plan_panel();
        
        if (callback != undefined) {
            callback();
        }
    });
}