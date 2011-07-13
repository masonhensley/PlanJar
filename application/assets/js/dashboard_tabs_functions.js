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
            show_data_container($(this).attr('assoc_div'), function (div_name) {
                // Call the associated initialization function.
                alert($(div_name).attr('setup_func') + '()');
                eval($(div_name).attr('setup_func') + '()');
            });
        }
    });
}

// Shows the data container specified in the argument.
function show_data_container(data_div, callback) {
    // Make callback optional.
    if (callback === undefined) {
        callback = function() {};
    }
    
    // Select the appropriate tab.
    $('.tab_container .tab').removeClass('tab_selected');
    $('.tab_container .tab[assoc_div="' + data_div + '"]').addClass('tab_selected');
        
    // Only show a container if it's not already visible.
    if ($(data_div).css('display') == 'none') {
        // Hide any visible data containers.
        $('.page_content:visible').hide('slide', {}, 'fast', function() {
            // Show the panel.
            console.log('showing');
            console.log($(data_div));
            $(data_div).show('slide', {}, 'fast', function () {
                callback(data_div);
            });
        });
    } else {
        callback(data_div);
    }
}