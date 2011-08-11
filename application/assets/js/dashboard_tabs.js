$(function() {
    initialize_dashboard_tabs();
});

// Initializes the map/data tabs.
function initialize_dashboard_tabs() {
    // Initial select is handled the dashboard view.
                
    // Click handler.
    $('.tab_container .tab').click(function () {
        show_data_container($(this).attr('assoc_div'));
    });
}

// Shows the data container specified in the argument.
function show_data_container(data_div) {
    $('#create_group').hide(); // hide the create group icon when the group tab isn't selected
    
    // Select the appropriate tab.
    $('.tab_container .tab').removeClass('tab_selected');
    $('.tab_container .tab[assoc_div="' + data_div + '"]').addClass('tab_selected');
        
    // Only show a container if it's not already visible.
    if ($(data_div).css('display') == 'none') {
        if ($('.page_content:visible').length == 0) {
            // No shown containers. Show the specified container.
            $(data_div).show('slide', {}, 'fast');
        } else {
            // Hide any visible data containers.
            $('.page_content:visible').hide('slide', {}, 'fast', function() {
                // Show the panel.
                $(data_div).show('slide', {}, 'fast');
            });
        }
    }
    
    // Call the setup function.
    eval($(data_div).attr('setup_func') + "()");
}

