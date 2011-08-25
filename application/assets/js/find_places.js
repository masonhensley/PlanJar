$(function() {
    initialize_find_places();
});

function initialize_find_places() {
    $('#find_places').click(function() {
        $(this).addClass('selected');
        show_data_container('#info_content')
        display_info();
    });
}