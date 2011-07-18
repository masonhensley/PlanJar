$(function() {
    show_selected_location();
});

function show_selected_location() {
    $('div.location_tab_shown').click(function() {
        
        if($(this).hasClass('selected_location'))
        {
            $(this).removeClass('selected_location');
        }else{
            $('.selected_location').removeClass('selected_location');
            $(this).addClass('selected_location');
            $.get('/home/show_location_data', {
                'place_id': $('.selected_location').attr('place_id'),
                'date': $('.selected_location').attr('date')
            }, function (data) {
                $('#location_data').html(data);        
            });
        }
        show_data_container('#location_data'); 
    });
}