$(function() {
    populate_plan_panel();
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
    // hide_data_containers()
    // show_data_container('#map_data')
    
    $('div.plan_content').click(function() {
        if(!$(this).hasClass('selected_plan'))
        {
            $('.selected_plan').removeClass('selected_plan');
            $(this).addClass('selected_plan');
            get_plan_data();
            show_data_container('#plan_data');
        }else{
            $(this).removeClass('selected_plan');
            hide_data_containers();
            // replace with a select plan message
            var replace_div = "<div id=\"plan_data_tab\" style=\"background-color: white; color:black; width: 555px; height:250px;\"> <p>Select one of your plans on the right to see more detailed information.</p></div>";
            $('#plan_data').html(replace_div); 
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
    });
}
