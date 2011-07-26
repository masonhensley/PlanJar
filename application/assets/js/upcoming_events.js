$(function() {
    initialize_event_tabs();
});

function initialize_event_tabs()
{
    $('.event_tab').click(function(){
        if(!$(this).hasClass('event_tab_active'))
        {
            $('.event_tab_active').remove_Class('event_tab_active');
            $(this).addClass('.event_tab_active');
            $.get('/home/show_location_data', {
                'place_id': $('.selected_location_tab').attr('place_id'),
                'date': $('.selected_location_tab').attr('date'),
                'selected_groups':get_selected_groups()
            }, function (data) {
                $('#location_data').html(data);
            });
        }
        show_data_container('#location_data');
    });
}