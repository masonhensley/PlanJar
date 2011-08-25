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
            deselect_all_controlls(); // No plan selected. Deselect all controlls
            $(this).addClass('selected_plan'); // Select this plan
            load_comment_section(); //load comments
        } else {
            // Deselect this plan
            $(this).removeClass('selected_plan');
            show_invite_link();
        }
        
        // Display the info box
        display_info(); 
        
    });
    
    // Submit comment click handler
    $('body').delegate('.submit_comment', 'click', function(){
        if($('#comment_area').val() != 'Leave a comment for this event...')
        {
            $.get('/home/submit_comment', {
                plan_id : $('.selected_plan').attr('plan_id'),
                comment : $('#comment_area').val()
            },
            function(){
                load_comment_section();
            });       
        }
        
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

function load_comment_section()
{
    // fill the comment box
    $('#comment_area').removeClass('comment_area_selected');
    
    // Load the comment box and comments
    $('.bottom_right_section').hide('fast');
    $('.comment_box').show('fast');
    $('#comment_area').val('Leave a comment for this event...');
    
    $('#comment_area').click(function(){ // click handler for the textarea
        if(!$(this).hasClass('comment_area_selected'))
        {
            $('#comment_area').addClass('comment_area_selected');
            $('#comment_area').val('');
            document.getElementById("comment_area").select()
        }
    });
    load_comments();
}

function load_comments(){
    $.get('/home/plan_comments', {
        plan_id : $('.selected_plan').attr('plan_id')
    }, 
    function(data){
        $('.plan_comments').html(data); // populate and show the comments
        $('.plan_comments').show('fast');
    });
}

function show_invite_link(){
    $('.plan_comments').hide();
    $('.comment_box').hide('fast');
    $('.bottom_right_section').show('fast');
}
