$(function() {
    initialize_plan_panel();
});

function initialize_plan_panel(){
    $('div.plans_wrapper li').click(function() {
        
        // Make the list tiems togglable.
        if ($(this).hasClass('plan_content')) {
            $(this).addClass('selected_plan');
        }
        
        // Call the callback function.
        on_groups_change();
    });
    
    // Initialize the clear all and select all button actions.
    $('#clear_all_groups').click(function() {
        $('div.group_selectable_wrapper li.group_selected').removeClass('group_selected');
        on_groups_change();
    });
    $('#select_all_groups').click(function() {
        $('div.group_selectable_wrapper li').addClass('group_selected');
        on_groups_change();
    });
}

/*
$( "#plans" ).tabs({
        select: function(event, ui){
         
            $(this).addClass('active_plan');
         
            $.get('/home/get_plan_data', {
                'plan_selected': $('.active_plan').attr('plan_id')
            }, function (data) {
                // Replace the data and show the data tab.
                alert(data);
                $('#data_tab').html(data);
                if ($("#map_data_tabs .ui-state-active a").attr('href') != '#data_tab') {
                    $("#map_data_tabs").tabs('select', '#data_tab');
                }
            });
            
            $(this).removeClass('active_plan');
        
        }
    });
    
    */
