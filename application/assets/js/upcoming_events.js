$(function() {
    initialize_event_tabs();
});

function initialize_event_tabs()
{
    $('.event_tab').click(function(){
        $('.selected_location_tab').removeClass('selected_location_tab');
        if(!$(this).hasClass('event_tab_active'))
        {
            $('.event_tab_active').removeClass('event_tab_active');
            $(this).addClass('event_tab_active');
            $.get('/home/show_event_data', {
                'place_id': $('.event_tab_active').attr('place_id')
            }, function (data) {
                $('#info_tab').html(data);
            });
        }
        show_data_container('#info_tab');
    });
}