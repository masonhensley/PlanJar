$(function() {
    initialize_dashboard_tabs();
});

// Initializes the map/data tabs.
function initialize_dashboard_tabs() {
    // Initial select
    show_data_container('#friends_content');
                
    // Click handler.
    $('.tab_container .tab').click(function () {
        if (!$(this).hasClass('tab_selected')) {
            show_data_container($(this).attr('assoc_div'));
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
    console.log('.tab_container .tab[assoc_div="' + data_div + '"]');
    console.log($('.tab_container .tab[assoc_div="' + data_div + '"]'));
    $('.tab_container .tab[assoc_div="' + data_div + '"]').addClass('tab_selected');
        
    // Only show a container if it's not already visible.
    if ($(data_div).css('display') == 'none') {
        if ($('.page_content:visible').length > 0) {
            // Hide any visible data containers.
            $('.page_content:visible').hide('slide', {}, 'fast', function() {
                // Show the panel.
                $(data_div).show('slide', {}, 'fast', function () {
                    callback();
                });
            });
        } else {
            // Show the panel.
            $(data_div).show('slide', {}, 'fast', function () {
                callback();
            });
        }
    } else {
        callback();
    }
}