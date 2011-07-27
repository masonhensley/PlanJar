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
    $('div.plan_content').click(function() {
        if(!$(this).hasClass('selected_plan'))
        {
            $('.selected_plan').removeClass('selected_plan');
            $(this).addClass('selected_plan');
            get_plan_data();
            show_data_container('#plan_data');
        }else{
            $(this).removeClass('selected_plan');
        // put code in here for when a plan is de-selected
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
        $('#plan_data').html(data.html);
        
        // Handles clicking on the delete plan button
        $('.delete_plan').click(delete_plan_outer_click);
        
        // Handles clicking on invite people
        $('.invite_people').click(function () {
            open_invite_modal(data.privacy, 'event');
        });
    });
}

function delete_plan_outer_click(event) {
    // Stop propagation (to allow for clicking anywhere BUT the element)
    event.stopPropagation();

    // Clear previous handlers
    $(this).unbind('click');
    $('html').unbind('click');
    
    // Get the original text
    var orig_text = $(this).html();
        
    // Replacement text
    $(this).html('Sure?');
        
    // Assign a click event to actually delete the plan
    $(this).click(function(event){
        // Stop propagation (to allow for clicking anywhere BUT the element)
        event.stopPropagation();
        
        $.get('/home/delete_plan', {
            'plan_selected': $('.selected_plan').attr('plan_id')
        }, function (data) {
            // Replace the data and show the data tab.
            $('#plan_data').html(data);
            populate_plan_panel();
        }); 
    });
        
    $('html').click(function() {
        // Replace the original text and re-assign the click event
        $('.delete_plan').html(orig_text);
        $('.delete_plan').unbind('click');
        $('.delete_plan').click(delete_plan_outer_click);
    });
}