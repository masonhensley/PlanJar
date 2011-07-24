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
        // Replace the data and show the data tab.
        $('#plan_data').html(data);
        delete_user_plan();
    });
}

// Handles clicking on the delete plan button
function delete_user_plan() {
    $('.delete_plan').one('click', delete_plan_outer_click);
}

function delete_plan_outer_click() {
    // Get the original text
    var orig_text = $(this).html();
        
    // Replacement text
    console.log($(this));
    console.log($('.delete_plan').html());
    $('.delete_plan').html('Sure?');
    console.log($(this));
        
    // Assign a one-time click event to actually delete the plan
    $(this).one('click', function(event){
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
        
    $('html').one('click', function () {
        // Replace the original text and re-assign the one-time click event
        $('.delete_plan').html(orig_text);
        $('.delete_plan').one('click', delete_plan_outer_click);
    });
}