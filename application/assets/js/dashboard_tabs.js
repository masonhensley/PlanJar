
$(function() {
    initialize_dashboard_tabs();
});

// Initializes the map/data tabs.
function initialize_dashboard_tabs() {
    // Initial select is handled the dashboard view.
                
    // Click handler.
    $('.tab_container .tab').click(function () {
        if (!$(this).hasClass('tab_selected')) {
            $('#create_group').hide(); // hide the create group icon when the group tab isn't selected'
            show_data_container($(this).attr('assoc_div'));
        } else {
            // Call the js setup function again (reload)
            eval($($(this).attr('assoc_div')).attr('setup_func') + '()');
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
        if ($('.page_content:visible').length == 0) {
            // No shown containers. Show the specified container.
            $(data_div).show('slide', {}, 'fast', function () {
                // Call the setup function.
                eval($(data_div).attr('setup_func') + "()");
            });
        } else {
            // Hide any visible data containers.
            $('.page_content:visible').hide('slide', {}, 'fast', function() {
                // Show the panel.
                $(data_div).show('slide', {}, 'fast', function () {
                    if ($(data_div).attr('setup_func') != undefined) {
                        // Call the setup function.
                        eval($(data_div).attr('setup_func') + "()");
                    }
                });
            });
        }
    } else {
        // Call the setup function.
        eval($(data_div).attr('setup_func') + "()");
    }
    
    // --------- HANDLER FOR GROUP TAB -------------
    if(data_div == '#groups_content')
    {
        // if the group tab is selected, show the + Create Group button
        $('#create_group').show("fast");
    }else if(data_div == '#profile_content') // --------------- HANDLER FOR PROFILE TAB --------------
    {
        $.get('/dashboard/get_profile',  {
            'user_id': 'user'
        },function (data) {
            $('.profile_box').html(data); 
            $('#box_text_area').hide();
            $('.update_box').hide();
            
            // click handler for edit box
            $('.edit_box').click(function(){
                $('.my_box').hide();
                $('.edit_box').hide();
                $('#box_text_area').show();
                
                // make sure the text clears when you click the first time, but not subsequent times
                $('#box_text_area').click(function(){
                    if(!$('#box_text_area').hasClass('box_text_area_selected'))
                    {
                        $('#box_text_area').addClass('box_text_area_selected');
                        $('#box_text_area').val('');
                    }
                });
                
                $('.update_box').show();
                
                // click handler for updating box
                $('.update_box').click(function(){
                    $.get('/dashboard/update_box', {
                        'box_text':$('#box_text_area').val()
                    }, function (data) {
                        $('.my_box').html(data);
                        $('.my_box').show("slow");
                        $('.update_box').hide();
                        $('#box_text_area').hide();
                    });
                });
            });
        });
    }
}