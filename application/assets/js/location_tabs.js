$(function() {
    show_selected_location();
});

function show_selected_location() {
    $('div.plan_shown').click(function() {
       $('.selected_location').removeClass('selected_location');
       $(this).addClass('selected_location');
       show_data_container('#location_data');  
    });
}