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
    $('div.plans_wrapper li').click(function() {
        addclass = $(this).hasClass('selected_plan');
        $('.selected_plan').removeClass('selected_plan');
        //$('.plan_content').removeClass('selected_plan');
        if(!addclass)
        {
            $(this).addClass('selected_plan');
            get_plan_data();
            // open the tab if it isn't already open
            if ($("#map_data_tabs .ui-state-active a").attr('href') != '#plan_data_tab' ) {
                $("#map_data_tabs").tabs('select', '#plan_data_tab');
            }
        }else{
            // close the tab when an active plan is clicked on again
            $("#map_data_tabs").tabs('select', '#plan_data_tab');
            var replace_div = "<div id=\"plan_data_tab\" style=\"background-color: white; color:black; width: 555px; height:250px;\"> <p>Select one of your plans on the right to see more detailed information.</p></div>";
            $('#plan_data_tab').html(replace_div);  
        }
    });    
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
