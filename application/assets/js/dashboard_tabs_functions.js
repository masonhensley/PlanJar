$(function() {
    initialize_dashboard_tabs();
});

// Initializes the map/data tabs.
function initialize_dashboard_tabs() {
    // Initial select
    show_data_container('#following_content');
                
    // Click handler.
    $('.tab_container .tab').click(function () {
        if (!$(this).hasClass('tab_selected')) {
            show_data_container($(this).attr('assoc_div'));
        }
    });
}

// Shows the data container specified in the argument.
function show_data_container(data_div) {
    
    // Select the appropriate tab.
    $('.tab_container .tab').removeClass('tab_selected');
    $('.tab_container .tab[assoc_div="' + data_div + '"]').addClass('tab_selected');
        
    // Only show a container if it's not already visible.
    if ($(data_div).css('display') == 'none') {
        // Hide any visible data containers.
        console.log('here');
        if ($('.page_content:visible') == []) {
            $(data_div).show('slide', {}, 'fast', function () {
                eval($(data_div).attr('setup_func') + "()");
            });
        } else {
            $('.page_content:visible').hide('slide', {}, 'fast', function() {
                // Show the panel.
                $(data_div).show('slide', {}, 'fast', function () {
                    eval($(data_div).attr('setup_func') + "()");
                });
            });
        }
    } else {
        eval($(data_div).attr('setup_func') + "()");
    }
}