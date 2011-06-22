$(function() {
    populate_plan_panel();
    $("div.plans_wrapper li:first").addClass("selected_plan"); //Activate first tab
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
    $('div.plans_wrapper li').click(function() {
        
        // Make the list tiems togglable.
        if ($(this).hasClass('plan_content')) {
            $('.plan_content').removeClass('selected_plan');
            $(this).addClass('selected_plan');
        }
        
        get_plan_data();
        on_plan_select();
    });    
}

// Callback function
function on_plan_select() {
    // select the plan data tab
    if ($("#map_data_tabs .ui-state-active a").attr('href') != '#plan_data_tab') {
        $("#map_data_tabs").tabs('select', '#plan_data_tab');
    }
}

// fetch the data about the plan and display it in the plan data div
function get_plan_data() {
    $.get('/home/get_plan_data', {
        'plan_selected': $('.selected_plan').attr('plan_id')
    }, function (data) {
        // Replace the data and show the data tab.
        $('#plan_data_tab').html(data);
    });
}