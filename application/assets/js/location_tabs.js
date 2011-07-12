$(function() {
    show_selected_location();
});

function show_selected_location() {
    $('div.plan_shown').click(function() {
        
        if($(this).hasClass('selected_location'))
        {
            $(this).removeClass('selected_location')
        }else{
            $('.selected_location').removeClass('selected_location');
            $(this).addClass('selected_location');
        }
        
       
       
        $.get('/home/show_location_data', {
            'plan_selected': $('.selected_plan').attr('plan_id')
        }, function (data) {
            // Replace the data and show the data tab.
            $('#plan_data').html(data);

            show_data_container('#location_data');  
        });
            
    });
}