$(function() {
    initialize_plan_panel();
});

// Sets up the plan panel
function initialize_plan_panel(){
    // Click handler
    $('.plan_content').click(function() {
        // Show the info tab if a plan wasn't already selected
        if ($('.selected_plan').length == 0) {
            show_data_container('#info_content'); 
        }
        
        if(!$(this).hasClass('selected_plan'))
        {
            // No plan selected. Deselect all controlls
            deselect_all_controlls();
            
            // Select this plan
            $(this).addClass('selected_plan');
        } else {
            // Deselect this plan
            $(this).removeClass('selected_plan');
        }
        
        // Display the info box
        display_info(); 
        
        // Load the comment box and comments
        $('.bottom_right_section').hide('fast');
        $('.comment_box').show('fast');
        $('.submit_comment').click(function(){
            $.get('/home/submit_comment', {
                plan_id : $('.selected_plan').attr('plan_id'),
                comment : $('#comment_area').val()
            },
            function(){
                load_comments();
            });
        });
        
        load_comments();
    });
    
    // View map
    $('.view_plan_map').click(function() {
        // Click the first plan in the set
        $(this).parent().next().not('.selected_plan').click();
        show_data_container('#map_content'); 
        
        return false;
    });
}

// Populates the plan panel (panel is pre-populated in PHP)
function populate_plan_panel(callback) {
    $.get('/home/get_my_plans', function (data) {
        $('div.plans_wrapper').html(data);
        
        initialize_plan_panel();
        
        if (callback != undefined) {
            callback();
        }
    });
}

function load_comments(){
    $.get('/home/plan_comments', {
        plan_id : $('.selected_plan').attr('plan_id')
    }, 
    function(data){
        
        $('.plan_comments').html(data);
        $('.plan_comments').show('fast');
        
        $('#comment_area').click(function(){
            if(!$(this).hasClass('comment_area_selected'))
            {
                $('#comment_area').addClass('comment_area_selected');
                $('#comment_area').val('');
                document.getElementById("comment_area").select()
            }
        });
    });
}
