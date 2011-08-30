// Called when the tab is selected
// Setp us the profile view
function setup_profile()
{
    console.log('here');
    $.get('/dashboard/get_profile',  {
        'user_id': 'user'
    }, function (data) {
        $('.profile_box').html(data); 
        $('#box_text_area').hide();
        $('.update_box').hide();
        setup_edit_box();
    });
}

// Sets up the edit box functionality
function setup_edit_box()
{
    // click handler for edit box
    $('.edit_box').click(function(){
        $('.my_box').hide();
        $('.edit_box').hide();
        $('#box_text_area').show();
        $('.update_box').show();
                
        // make sure the text clears when you click the first time, but not subsequent times
        $('#box_text_area').click(function(){
            if(!$('#box_text_area').hasClass('box_text_area_selected'))
            {
                $('#box_text_area').addClass('box_text_area_selected'); // right now this does nothing, but could be useful later
            }
        });
                
        // click handler for updating box
        $('.update_box').click(function(){
            
            // start spinner
            var target = document.getElementById('my_box_spinner');
            var opts = spinner_options();
            var spinner = new Spinner(opts).spin(target);
            
            $.get('/dashboard/update_box', {
                'box_text':$('#box_text_area').val()
            }, function (data) {
                setup_profile();
                spinner.stop();
            });
        });
    });
}