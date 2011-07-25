$(function() {
    show_selected_location();
});

function show_selected_location() {
    $('div.location_tab').click(function() {
        
        if($(this).hasClass('selected_location_tab'))
        {
            $(this).removeClass('selected_location_tab');
        }else{
            $('.selected_location_tab').removeClass('selected_location_tab');
            $(this).addClass('selected_location_tab');
            $.get('/home/show_location_data', {
                'place_id': $('.selected_location_tab').attr('place_id'),
                'date': $('.selected_location_tab').attr('date')
            }, function (data) {
                $('#location_data').html(data);        
            });
        }
        show_data_container('#location_data'); 
    });
}