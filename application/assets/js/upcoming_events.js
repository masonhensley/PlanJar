$(function() {
    initialize_event_tabs();
});

function initialize_event_tabs()
{
    $('.event_tab').click(function(){
        if(!$(this).hasClass('event_tab_active'))
        {
            $('.event_tab_active').removeClass('event_tab_active');
            $(this).addClass('event_tab_active');
            $.get('/home/show_event_data', {
                'place_id': $('.event_tab_active').attr('place_id'),
            }, function (data) {
                $('#location_data').html(data);
            });
        }
        show_data_container('#location_data');
    });
}