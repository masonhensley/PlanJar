$(function () {
    initialize_change_location_modal();
})

function initialize_change_location_modal() {
    $('#change_location_content').dialog(
    {
        autoOpen: false,
        width: 600,
        height: 250,
        resizable: false,
        show: 'clip',
        hide: 'explode'
    });
    
    $('#change_location').click(function () {
        $('#change_location_content').dialog('open');
        return false;
    });
}